<?php
/**
 * Application module
 * 
 * @package Application
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */

namespace Application\Factory;

use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\Contact;
use Application\Model\ContactTable;

/**
 * Factory for contact table repository {@see Application\Model\ContactTable}
 */
class ContactTableFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * 
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Application\Model\ActionTable
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        // hydrator
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new Contact());

        $table = new ContactTable($dbAdapter, $resultSetPrototype);

        return $table;
    }
}
