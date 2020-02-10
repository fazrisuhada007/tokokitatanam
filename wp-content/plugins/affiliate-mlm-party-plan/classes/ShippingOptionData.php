<?php

namespace Unisho\Sb\Model;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ShippingOptionData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\Api\Data\ShippingOptionDataInterface
{
    protected $_data = array(
        'Rate' => 0.0,
        'Name' => '',
        'Description' => '',
    );

    public function getRate() {return $this->_data['Rate'];}
    public function getName() {return $this->_data['Name'];}
    public function getDescription() {return $this->_data['Description'];}

    public function setRate($rate) {$this->_data['Rate'] = $rate; return $this;}
    public function setName($name) {$this->_data['Name'] = $name; return $this;}
    public function setDescription($description) {$this->_data['Description'] = $description; return $this;}

    public function __toArray() {
        return $this->_data;
    }

    public function setData($data) {
        if(is_array($data) == false) {
            return $this;
        }
        foreach($data as $k => $v) {
            if(array_key_exists($k, $this->_data)) {
                $this->_data[$k] = $v;
            }
        }

        return $this;
    }
}
