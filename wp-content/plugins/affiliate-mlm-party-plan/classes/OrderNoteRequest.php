<?php

namespace Unisho\Sb\Model;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OrderNoteRequest extends \Unisho\Sb\DataStruct implements \Unisho\Sb\Api\Data\OrderNoteRequestInterface
{
    protected $_data = array(
        'OrderId' => 0,
        'Note' => '',
        'NotifyCustomer' => false,
        'DisplayToCustomer' => false,
    );

    public function getOrderId() {return $this->_data['OrderId'];}
    public function getNote() {return $this->_data['Note'];}
    public function getNotifyCustomer() {return $this->_data['NotifyCustomer'];}
    public function getDisplayToCustomer() {return $this->_data['DisplayToCustomer'];}

    public function setOrderId($order_id) {$this->_data['OrderId'] = (int)$order_id; return $this;}
    public function setNote($note) {$this->_data['Note'] = (string)$note; return $this;}
    public function setNotifyCustomer($notify_customer) {$this->_data['NotifyCustomer'] = (bool)$notify_customer; return $this;}
    public function setDisplayToCustomer($display) {$this->_data['DisplayToCustomer'] = (bool)$display; return $this;}

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