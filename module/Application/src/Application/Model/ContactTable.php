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
use Zend\Db\Sql\Expression;
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
     * Retrive all contacts with categories
     * 
     * @return ResultSet
     */
    public function fetchAll()
    {
        $expression = 'IFNULL(GROUP_CONCAT(category.category_name ORDER BY '
            . 'category.category_name ASC SEPARATOR \', \'), \'Besorolatlan\')';

        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $rows = $select
            ->columns(
                array(
                    'contact_id' => 'id',
                    'contact_name' => 'contact_name',
                    'contact_email' => 'contact_email',
                    'contact_cell' => 'contact_cell',
                    'contact_add_date' => 'contact_add_date',
                    'contact_categories' => new Expression($expression)
                )
            )
            ->from($this->table)
            // join contact-category table to get categories' id for contacts
            ->join(
                array('contact_category' => 'contact_category'), 
                'contact_category.contact_id = contact.id',
                array(),
                Select::JOIN_LEFT
            )
            // join category table to get category names for contacts
            ->join(
                array('category' => 'category'), 
                'category.id = contact_category.category_id',
                array('category_name' => 'category_name'),
                Select::JOIN_LEFT
            )
            ->group('contact.id')
        ;
        // \Zend\Debug\Debug::dump($sql->getSqlStringForSqlObject($select));
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($result);

        return $resultSet;
    }
}
