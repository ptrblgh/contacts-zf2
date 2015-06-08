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
use Application\Model\Contact;
use Application\Form\ContactForm;
use Application\Form\Filter\ContactInputFilter;
use Application\Model\CategoryTable;
use Application\Model\ContactTable;

/**
 * Controller for contact actions
 *
 * {@inheritDoc}
 */
class ContactController extends AbstractActionController
{
    /**
     * @var ContactTable
     */
    protected $contactTable;

    /**
     * @var ContactInputFilter
     */
    protected $contactInputFilter;

    /**
     * @var array
     */
    protected $appConfig;

    /**
     * @var CategoryTable
     */
    protected $categoryTable;

    /**
     * Controller constructor
     *
     * @param ContactTable $contactTable
     */
    public function __construct(
        ContactTable $contactTable,
        ContactInputFilter $contactInputFilter,
        $appConfig,
        CategoryTable $categoryTable
    ) {
        $this->contactTable = $contactTable;
        $this->contactInputFilter = $contactInputFilter;
        $this->appConfig = $appConfig;
        $this->categoryTable = $categoryTable;
    }

    /**
     * List of contacts
     * 
     * @return ViewModel
     */
    public function listAction()
    {
        $order_by = $this->params()->fromRoute('order_by', 'contact_name');
        $order = $this->params()->fromRoute('order', 'ASC');

        $page = $this->params()->fromRoute('page') 
            ? (int) $this->params()->fromRoute('page') 
            : 1
        ;

        $params = array(
            'order_by' => $order_by, 
            'order' => $order
        );

        $contacts = $this->contactTable->fetchAll($params);

        $itemsPerPage = $this->appConfig['items_per_page'];

        $contacts->current();
        $paginator = new Paginator(new paginatorIterator($contacts));
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
     * Add contact with categories
     * 
     * @return ViewModel|\Zend\Http\Response
     */
    public function addAction()
    {
        $form = new ContactForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $contact = new Contact();
            $inputFilter = $this->contactInputFilter;
            $form->setInputFilter($inputFilter->getInputFilter());
            $form->setValidationGroup(
                'contact_name', 
                'contact_email', 
                'contact_cell',
                'categories'
            );
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $contact->exchangeArray($form->getData());
                $this->contactTable->saveContact($contact);
                $this->contactTable->saveContactCategories($request->getPost());
                
                $this->redirect()->toRoute('contact');
            }
        }

        $params = array(
            'order_by' => 'category_name',
            'order' => 'ASC',
            'forSelect' => true
        );
        $categoryValues = $this->categoryTable->fetchAll($params);

        return array(
            'form' => $form, 
            'categoryValues' => $categoryValues,
        );
    }

    /**
     * Modify contacts
     * 
     * @return ViewModel|\Zend\Http\Response
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        try {
            $contact = $this->contactTable->getContact($id);
        } catch (\Exception $e) {
            $this->redirect()->toRoute('contact');
        }

        $form = new ContactForm();
        $form->bind($contact);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            $inputFilter = $this->contactInputFilter;
            $form->setInputFilter($inputFilter->getInputFilter($id));
            $form->setValidationGroup(
                'id', 
                'contact_name', 
                'contact_email', 
                'contact_cell'
            );

            if ($form->isValid()) {
                $this->contactTable->saveContact($contact);
                $this->categoryTable->deleteContactCategories($contact->id);
                $this->contactTable->saveContactCategories($request->getPost());
                
                $this->redirect()->toRoute('contact');
            }
        }

        $params = array(
            'order_by' => 'category_name',
            'order' => 'ASC',
            'forSelect' => true
        );
        $categoryValues = $this->categoryTable->fetchAll($params);
        $defaultCategoryValues 
            = $this->categoryTable->findCategoriesByContact($id);

        return array(
            'form' => $form, 
            'id' => $id,
            'categoryValues' => $categoryValues,
            'defaultCategoryValues' => $defaultCategoryValues,
        );
    }

    /**
     * Delete contacts
     * 
     * @return ViewModel|\Zend\Http\Response
     */
    public function delAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $this->contactTable->deleteContact($id);
        $this->categoryTable->deleteContactCategories($id);

        $this->redirect()->toRoute('contact');
    }
}
