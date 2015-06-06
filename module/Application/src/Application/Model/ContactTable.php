<?php
/**
 * Application module
 * 
 * @package Application
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */

namespace Application\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;


/**
 * Repository for contact
 * 
 * {@inheritdoc}
 */
class ContactTable extends AbstractTableGateway
{
    /**
     * @var string base table for TableGateway
     */
    protected $table = 'contact';

    /**
     * TableGateway constructor
     * 
     * @param Adapter $dbAdapter
     * @param HydratingResultSet $resultSetPrototype 
     */
    public function __construct(
        Adapter $dbAdapter, 
        HydratingResultSet $resultSetPrototype
    ) {
        $this->adapter = $dbAdapter;
        $this->resultSetPrototype = $resultSetPrototype;

        $this->initialize();
    }

    /**
     * Retrive all contacts
     * 
     * @return ResultSet
     */
    public function fetchAll()
    {
        $rows = $this->select();

        return $rows;
    }
}
