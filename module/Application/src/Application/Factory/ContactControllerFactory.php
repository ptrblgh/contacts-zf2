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

        return new ContactController();
    }
}