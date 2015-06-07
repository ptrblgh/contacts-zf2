<?php
/**
 * Application module
 * 
 * @package Application
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */

namespace Application\Model;

/**
 * Class for category entity object
 */
class Category
{
    public $id;
    public $category_name;
    public $category_add_date;

    /**
     * Populating entity properties
     * 
     * @param  array $data
     * @return void
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id']) ? $data['id'] : null);
        $this->category_name 
            = (isset($data['category_name']) ? $data['category_name'] : null);
        $this->category_add_date 
            = (isset($data['category_add_date']) 
                ? $data['category_add_date'] 
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
