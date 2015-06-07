<?php
/**
 * Application module
 * 
 * @package Application
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Zend\View\Model\ViewModel;
use Application\Form\CategoryForm;
use Application\Form\Filter\CategoryInputFilter;
use Application\Model\Category;
use Application\Model\CategoryTable;
use Application\Model\ContactTable;

/**
 * Controller for category actions
 *
 * {@inheritDoc}
 */
class CategoryController extends AbstractActionController
{
    /**
     * @var CategoryTable
     */
    protected $categoryTable;

    /**
     * @var CategoryInputFilter
     */
    protected $categoryInputFilter;

    /**
     * @var array
     */
    protected $appConfig;

    /**
     * @var ContactTable
     */
    protected $contactTable;

    /**
     * Controller constructor
     *
     * @param CategoryTable $categoryTable
     */
    public function __construct(
        CategoryTable $categoryTable,
        CategoryInputFilter $categoryInputFilter,
        $appConfig,
        ContactTable $contactTable
    ) {
        $this->categoryTable = $categoryTable;
        $this->categoryInputFilter = $categoryInputFilter;
        $this->appConfig = $appConfig;
        $this->contactTable = $contactTable;
    }

    /**
     * List of categories
     * 
     * @return ViewModel
     */
    public function listAction()
    {
        $order_by = $this->params()->fromRoute('order_by', 'category_name');
        $order = $this->params()->fromRoute('order', 'ASC');

        $page = $this->params()->fromRoute('page') 
            ? (int) $this->params()->fromRoute('page') 
            : 1
        ;

        $params = array(
            'order_by' => $order_by, 
            'order' => $order,
            'forSelect' => false
        );

        $categories = $this->categoryTable->fetchAll($params);

        $itemsPerPage = $this->appConfig['items_per_page'];

        $paginator = new Paginator(new paginatorIterator($categories));
        $paginator->setCurrentPageNumber($page)
            ->setItemCountPerPage($itemsPerPage)
            ->setPageRange(5);

        return array(
            'order_by' => $order_by,
            'order' => $order,
            'paginator' => $paginator
        );
    }

    /**
     * Add category with categories
     * 
     * @return ViewModel|\Zend\Http\Response
     */
    public function addAction()
    {
        $form = new CategoryForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $category = new Category();
            $inputFilter = $this->categoryInputFilter;
            $form->setInputFilter($inputFilter->getInputFilter());
            $form->setValidationGroup('category_name');
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category->exchangeArray($form->getData());
                $this->categoryTable->saveCategory($category);
                
                $this->redirect()->toRoute('category');
            }
        }

        return array('form' => $form);
    }

    /**
     * Modify category
     * 
     * @return ViewModel|\Zend\Http\Response
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        try {
            $category = $this->categoryTable->getCategory($id);
        } catch (\Exception $e) {
            
            $this->redirect()->toRoute('category');
        }

        $form = new CategoryForm();
        $form->bind($category);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            $inputFilter = $this->categoryInputFilter;
            $form->setInputFilter($inputFilter->getInputFilter($id));
            $form->setValidationGroup('id', 'category_name');

            if ($form->isValid()) {
                $this->categoryTable->saveCategory($category);

                $this->redirect()->toRoute('category');
            }
        }

        return array('form' => $form, 'id' => $id);
    }

    /**
     * Delete category
     * 
     * @return ViewModel|\Zend\Http\Response
     */
    public function delAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $this->categoryTable->deleteCategory($id);
        $this->contactTable->saveUncategorizedContacts();

        $this->redirect()->toRoute('category');
    }
}
