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
use Application\Controller\CategoryController;

/**
 * Factory for instantiating {@see Application\Controller\CategoryController}
 */
class CategoryControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * 
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Application\Controller\CategoryController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        $categoryTable = $parentLocator->get('category_table');
        $categoryInputFilter = $parentLocator->get('category_input_filter');
        $appConfig = $parentLocator->get('config');
        $contactTable = $parentLocator->get('contact_table');

        return new CategoryController(
            $categoryTable, 
            $categoryInputFilter,
            $appConfig,
            $contactTable
        );
    }
}
