<?php
/**
 * Application module
 * 
 * @package Application
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */

namespace Application\Model;

/**
 * Class for contact entity object
 */
class Contact
{
    public $id;
    public $contact_name;
    public $contact_email;
    public $contact_cell;
    public $contact_add_date;

    /**
     * Populating entity properties
     * 
     * @param  array $data
     * @return void
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id']) ? $data['id'] : null);
        $this->contact_name 
            = (isset($data['contact_name']) ? $data['contact_name'] : null);
        $this->contact_email 
            = (isset($data['contact_email']) ? $data['contact_email'] : null);
        $this->contact_cell 
            = (isset($data['contact_cell']) ? $data['contact_cell'] : null);
        $this->contact_add_date 
            = (isset($data['contact_add_date']) 
                ? $data['contact_add_date'] 
                : null
            );
    }

    /**
     * Retriving entity properties
     * 
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
