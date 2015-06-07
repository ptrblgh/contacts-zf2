<?php
/**
 * Application module
 * 
 * @package Application
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Contact;
use Application\Form\ContactForm;
use Application\Form\Filter\ContactInputFilter;
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
     * Controller constructor
     *
     * @param ContactTable $contactTable
     */
    public function __construct(
        ContactTable $contactTable,
        ContactInputFilter $contactInputFilter
    ) {
        $this->contactTable = $contactTable;
        $this->contactInputFilter = $contactInputFilter;
    }

    /**
     * List of contacts
     * 
     * @return ViewModel
     */
    public function listAction()
    {
        $contacts = $this->contactTable->fetchAll();

        return array('contacts' => $contacts);
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
                //$this->categoryTable->saveCategories($request->getPost());
                
                $this->redirect()->toRoute('contact');
            }
        }

        //$categoryValues = $this->categoryTable->fetchAllForSelect();

        return array(
            'form' => $form, 
            //'categoryValues' => $categoryValues,
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
            echo $e->getMessage();
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
                //$this->categoryTable->deleteCategories($contact->id);
                //$this->categoryTable->saveCategories($request->getPost());
                
                $this->redirect()->toRoute('contact');
            }
        }

        // $categoryValues = $this->categoryTable->fetchAllForSelect();
        // $defaultCategoryValues 
        //     = $this->categoryTable->findCategoriesByContact($id);

        return array(
            'form' => $form, 
            'id' => $id,
            //'categoryValues' => $categoryValues,
            //'defaultCategoryValues' => $defaultCategoryValues,
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
        //$this->roleTable->deleteContactCategories($id);

        $this->redirect()->toRoute('contact');
    }
}
