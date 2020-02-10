<?php

namespace Unisho\Sb;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DiscountResultData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\DiscountResultDataInterface
{
    protected $_data = array(
        'Id' => 0,
        'Coupon' => '',
        'Success' => false,
        'Discount' => null,
    );

    public function getId() {return $this->_data['Id'];}
    public function getCoupon() {return $this->_data['Coupon'];}
    public function getSuccess() {return $this->_data['Success'];}
    public function getDiscount() {return $this->_data['Discount'];}

    public function setId($id) {$this->_data['Id'] = (int)$id; return $this;}
    public function setCoupon($coupon) {$this->_data['Coupon'] = (string)$coupon; return $this;}
    public function setSuccess($success) {$this->_data['Success'] = (bool)$success; return $this;}
    public function setDiscount($discount) {$this->_data['Discount'] = $discount; return $this;}
}
