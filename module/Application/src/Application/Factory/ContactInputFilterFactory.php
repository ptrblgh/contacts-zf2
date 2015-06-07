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
use Application\Form\Filter\ContactInputFilter;

/**
 * Factory for instantiating {@see Application\Form\Filter\ContactInputFilter}
 */
class ContactInputFilterFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * 
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Application\Form\Filter\ContactInputFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $contactTable = $serviceLocator->get('contact_table');
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        return new ContactInputFilter($dbAdapter, $contactTable);
    }
}
