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
use Application\Model\ContactTable;

/**
 * Validatotion rules for contacts
 */
class ContactInputFilter implements InputFilterAwareInterface
{
    /**
     * @var Adapter
     */
    protected $dbAdapter;

    /**
     * @var RoleTable
     */    
    protected $contactTable;

    /**
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * InputFilter Constructor
     * 
     * @param Adapter $dbAdapter 
     * @param ContactTable $contactTable
     */
    public function __construct(Adapter $dbAdapter, ContactTable $contactTable)
    {
        $this->dbAdapter = $dbAdapter;
        $this->contactTable = $contactTable;
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
     * @param  integer $contactId
     * @return InputFilter
     */
    public function getInputFilter($contactId = 0)
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
                'name' => 'contact_name',
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
                            'table' => $this->contactTable->getTable(),
                            'field' => 'contact_name',
                            'adapter' => $this->dbAdapter,
                            'exclude' => array(
                                'field' => 'id',
                                'value' => $contactId
                            )
                        )
                    )
                ),
            ));

            $inputFilter->add(array(
                'name' => 'contact_email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'utf-8',
                            'min' => 5,
                            'max' => 255,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'contact_cell',
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' 
                                => '/^(36)(20|30|31|70){1}([1-9]{1})([0-9]{6})$/'
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'categories',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;

    }
}
