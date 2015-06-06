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

/**
 * Controller for contact actions
 * 
 * {@inheritDoc}
 */
class ContactController extends AbstractActionController
{
    /**
     * Controller constructor
     * 
     */
    public function __construct() {

    }

    /**
     * List of contacts
     * 
     * @return ViewModel
     */
    public function listAction()
    {
        return array();
    }
}
