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
     * @param array $params
     * @return ResultSet
     */
    public function fetchAll($params)
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
        if (is_array($params) 
            && !empty($params['order_by'])
            && !empty($params['order'])
        ) {
            $rows->order($params['order_by'] . ' ' . $params['order']);
        }

        // \Zend\Debug\Debug::dump($sql->getSqlStringForSqlObject($select));
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($result);

        $resultSet->buffer();

        return $resultSet;
    }

    /**
     * Save contact
     * 
     * @param Contact $contact
     * @return void|\Exception
     */
    public function saveContact($contact)
    {
        $data = array(
            'contact_name' => $contact->contact_name,
            'contact_email' => $contact->contact_email,
            'contact_cell' => $contact->contact_cell,
        );

        $id = (int) $contact->id;

        if ($id == 0) {
            $this->insert($data);
        } else {
            $row = $this->getContact($id);
            if ($row) {
                $this->update($data, array('id' => $id));
            } else {
                $msg = "A cím nem létezik.";
                throw new \Exception($msg);
            }
        }
    }

    /**
     * Get contact
     * 
     * @param int $contactId
     * @throws \Exception if contact doesn't exist
     * @return ResultSet
     */
    public function getContact($id)
    {
        $id = (int) $id;

        $contact = $this->select(array('id' => $id));
        if (!$contact) {
            $msg = "A cím nem létezik.";
            throw new \Exception($msg);
        }

        return $contact->current();
    }

    /**
     * Delete contact
     * 
     * @param int $id
     * @return void
     */
    public function deleteContact($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}
