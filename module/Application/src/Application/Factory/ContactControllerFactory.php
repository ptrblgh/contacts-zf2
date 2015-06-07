<?php
/**
 * Application module
 * 
 * @package Application
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */

namespace Application\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\ContactController;

/**
 * Factory for instantiating {@see Application\Controller\ContactActionController}
 */
class ContactControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * 
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Application\Controller\ContactController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        $contactTable = $parentLocator->get('contact_table');
        $contactInputFilter = $parentLocator->get('contact_input_filter');
        $appConfig = $parentLocator->get('config');
        $categoryTable = $parentLocator->get('category_table');

        return new ContactController(
            $contactTable, 
            $contactInputFilter,
            $appConfig,
            $categoryTable
        );
    }
}
