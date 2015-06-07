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
 * Repository for category
 * 
 * {@inheritdoc}
 */
class CategoryTable extends AbstractTableGateway
{
    /**
     * @var string base table for TableGateway
     */
    protected $table = 'category';

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
     * Retrive all categorys with categories
     * 
     * @param array $params
     * @return ResultSet
     */
    public function fetchAll($params)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $rows = $select
            ->columns(
                array(
                    'category_id' => 'id',
                    'category_name' => 'category_name',
                    'category_add_date' => 'category_add_date',
                )
            )
            ->from($this->table)
            ->where('id != 1')
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

        $rowsForSelect = array();
        foreach ($resultSet as $row) {
            $rowsForSelect[$row->category_id] = $row->category_name;
        }

        return ($params['forSelect']) ? $rowsForSelect : $resultSet;
    }

    /**
     * Save category
     * 
     * @param Category $category
     * @return void|\Exception
     */
    public function saveCategory($category)
    {
        $data = array('category_name' => $category->category_name);

        $id = (int) $category->id;

        if ($id == 0) {
            $this->insert($data);
        } else {
            $row = $this->getCategory($id);
            if ($row) {
                $this->update($data, array('id' => $id));
            } else {
                $msg = "A kategória nem létezik.";
                throw new \Exception($msg);
            }
        }
    }

    /**
     * Get category
     * 
     * @param int $id
     * @throws \Exception if category doesn't exist
     * @return ResultSet
     */
    public function getCategory($id)
    {
        $id = (int) $id;

        // Do not get 'Besorolatlan' category
        $category = $this->select(function (Select $select) use ($id) {
            $select->where('id = ' . $id . ' AND id != 1');
        });
        if ($category->count() == 0) {
            $msg = "A kategória nem létezik.";
            throw new \Exception($msg);
        }
        return $category->current();
    }

    /**
     * Delete category
     * 
     * @param int $id
     * @return void
     */
    public function deleteCategory($id)
    {
        // do not delete 'Besorolatlan' category
        if ((int) $id !==  1) {
            $this->delete(array('id' => (int) $id));

            // delete bridge table entries too
            $sql = new Sql($this->adapter);
            $delete = $sql
                ->delete('contact_category')
                ->where('category_id = ' . $id);

            $statement = $sql->prepareStatementForSqlObject($delete);
            $result = $statement->execute();
        }
    }

    /**
     * Delete category-contact pairs from bridge table by contact id
     * 
     * @param int $contactId
     * @return void
     */
    public function deleteContactCategories($contactId)
    {
        $contactId = (int) $contactId;
        $sql = new Sql($this->adapter);
        $delete = $sql
            ->delete('contact_category')
            ->where('contact_id = ' . $contactId);

        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();
    }

    /**
     * Find categories by contact id
     * 
     * @param int $contactId
     * @param boolean $forSelect (optional)
     * @return ResultSet|array
     */
    public function findCategoriesByContact($contactId, $forSelect = true)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();        
        $rows = $select
            ->columns(array('category_id' => 'id'))
            ->from($this->table)
            ->join(
                array('contact_category' => 'contact_category'), 
                'contact_category.category_id = category.id',
                array(),
                Select::JOIN_LEFT
            )
            ->where(array('contact_category.contact_id' => $contactId))
            ->order('category_id ASC');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($result);

        $valuesForSelect = array();
        foreach ($resultSet as $row) {
            $valuesForSelect[] = $row->category_id;
        }

        return ($forSelect) ? $valuesForSelect : $rows;
    }

}
