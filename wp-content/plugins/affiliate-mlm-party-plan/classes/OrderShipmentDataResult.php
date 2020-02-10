<?php

namespace Unisho\Sb\Model;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OrderShipmentDataResult extends \Unisho\Sb\DataStruct implements \Unisho\Sb\Api\Data\OrderShipmentDataResultInterface
{
    protected $_data = array(
        'OrderId' => 0,
        'CustomerId' => 0,
        'ShippingMethod' => '',
        'ShippingAddress' => null,
        'Shipments' => array(),
    );

    public function getOrderId() {return $this->_data['OrderId'];}
    public function getCustomerId() {return $this->_data['CustomerId'];}
    public function getShippingMethod() {return $this->_data['ShippingMethod'];}
    public function getShippingAddress() {return $this->_data['ShippingAddress'];}
    public function getShipments() {return $this->_data['Shipments'];}

    public function setOrderId($order_id) {$this->_data['OrderId'] = (int)$order_id; return $this;}
    public function setCustomerId($customer_id) {$this->_data['CustomerId'] = (int)$customer_id; return $this;}
    public function setShippingMethod($shipping_method) {$this->_data['ShippingMethod'] = (string)$shipping_method; return $this;}
    public function setShippingAddress($address) {$this->_data['ShippingAddress'] = $address; return $this;}
    public function setShipments($shipments) {$this->_data['Shipments'] = $shipments; return $this;}

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
