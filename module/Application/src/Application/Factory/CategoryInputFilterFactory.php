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
use Application\Form\Filter\CategoryInputFilter;

/**
 * Factory for instantiating {@see Application\Form\Filter\CategoryInputFilter}
 */
class CategoryInputFilterFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * 
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Application\Form\Filter\CategoryInputFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $categoryTable = $serviceLocator->get('category_table');
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        return new CategoryInputFilter($dbAdapter, $categoryTable);
    }
}
