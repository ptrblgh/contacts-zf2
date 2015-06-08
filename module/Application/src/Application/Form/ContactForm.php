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
 * Form elements for contact
 */
class ContactForm extends Form
{
    public function __construct()
    {
        parent::__construct('contact');

        $this->add(array(
            'name' => 'id', 
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'contact_name', 
            'type' => 'text',
            'options' => array(
                'label' => 'Név',
                'label_attributes' => array(
                    'for' => 'contact_name',
                    'class' => 'control-label full-width'
                ),
            ),
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'contact_email', 
            'type' => 'text',
            'options' => array(
                'label' => 'E-mail',
                'label_attributes' => array(
                    'for' => 'contact_email',
                    'class' => 'control-label full-width'
                ),
            ),
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'contact_cell', 
            'type' => 'text',
            'options' => array(
                'label' 
                    => 'Mobil (36xxyyyyyyy, xx értékei lehetnek: 20, 30, 31, 70)',
                'label_attributes' => array(
                    'for' => 'contact_cell',
                    'class' => 'control-label full-width'
                ),
            ),
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'categories', 
            'type' => 'select',
            'options' => array(
                'label' => 'Kategóriák',
                'label_attributes' => array(
                    'for' => 'categories',
                    'class' => 'control-label full-width'
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control js-example-responsive select2',
                'multiple' => 'multiple',
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
