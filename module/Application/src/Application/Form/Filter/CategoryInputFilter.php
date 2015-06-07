<?php
/**
 * Application module
 * 
 * @package Application
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */

namespace Application\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Db\Adapter\Adapter;
use Application\Model\CategoryTable;

/**
 * Validation rules for category
 */
class CategoryInputFilter implements InputFilterAwareInterface
{
    /**
     * @var Adapter
     */
    protected $dbAdapter;

    /**
     * @var RoleTable
     */    
    protected $categoryTable;

    /**
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * InputFilter Constructor
     * 
     * @param Adapter $dbAdapter 
     * @param CategoryTable $categoryTable
     */
    public function __construct(Adapter $dbAdapter, CategoryTable $categoryTable)
    {
        $this->dbAdapter = $dbAdapter;
        $this->categoryTable = $categoryTable;
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @throws Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new Exception('Not used.');        
    }

    /**
     * @param  integer $categoryId
     * @return InputFilter
     */
    public function getInputFilter($categoryId = 0)
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                ),
                'validators' => array(
                    array('name' => 'Digits')
                )
            ));

            $inputFilter->add(array(
                'name' => 'category_name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                    array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'utf-8',
                            'min' => 3,
                            'max' => 100,
                        ),
                    ),
                    array(
                        'name' => 'Db\NoRecordExists',
                        'options' => array(
                            'table' => $this->categoryTable->getTable(),
                            'field' => 'category_name',
                            'adapter' => $this->dbAdapter,
                            'exclude' => array(
                                'field' => 'id',
                                'value' => $categoryId
                            )
                        )
                    )
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;

    }
}
