<?php

namespace Unisho\Sb\Model;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ShippingRequestData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\Api\Data\ShippingRequestDataInterface
{
    protected $_data = array(
        'Customer' => null,
        'Products' => array(),
        'ShippingRateName' => '',
    );

    public function getCustomer() {return $this->_data['Customer'];}
    public function getProducts() {return $this->_data['Products'];}
    public function getShippingRateName() {return $this->_data['ShippingRateName'];}

    public function setCustomer($customer) {$this->_data['Customer'] = $customer; return $this;}
    public function setProducts($products) {$this->_data['Products'] = $products; return $this;}
    public function setShippingRateName($rate_name) {$this->_data['ShippingRateName'] = $rate_name; return $this;}
}
