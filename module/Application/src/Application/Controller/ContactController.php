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
     * Controller constructor
     *
     * @param ContactTable $contactTable
     */
    public function __construct(ContactTable $contactTable) {
        $this->contactTable = $contactTable;
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
}
