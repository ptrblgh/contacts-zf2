<?php
/**
 * Application module
 * 
 * @package Application
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Form elements for category
 */
class CategoryForm extends Form
{
    public function __construct()
    {
        parent::__construct('category');

        $this->add(array(
            'name' => 'id', 
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'category_name', 
            'type' => 'text',
            'options' => array(
                'label' => 'Név',
                'label_attributes' => array(
                    'for' => 'name',
                    'class' => 'control-label full-width'
                ),
            ),
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));

        $this->add(new Element\Csrf('security'));

        $this->add(array(
            'name' => 'add',
            'options' => array(
                'label' => 'Hozzáad'
            ),
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Hozzáad',
                'class' => 'btn btn-default'
            ),
        ));
    }
}
