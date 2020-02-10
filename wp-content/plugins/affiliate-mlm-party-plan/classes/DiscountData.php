<?php

namespace Unisho\Sb;

class DiscountData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\DiscountDataInterface
{
    protected $_data = array(
        'Id' => 0,
        'Name' => '',
        'UsePercentage' => false,
        'DiscountPercentage' => 0,
        'DiscountAmount' => 0,
    );

    public function getId() {return $this->_data['Id'];}
    public function getName() {return $this->_data['Name'];}
    public function getUsePercentage() {return $this->_data['UsePercentage'];}
    public function getDiscountPercentage() {return $this->_data['DiscountPercentage'];}
    public function getDiscountAmount() {return $this->_data['DiscountAmount'];}

    public function setId($id) {$this->_data['Id'] = (int)$id; return $this;}
    public function setName($name) {$this->_data['Name'] = (string)$name; return $this;}
    public function setUsePercentage($use_percentage) {$this->_data['UsePercentage'] = (bool)$use_percentage; return $this;}
    public function setDiscountPercentage($discount) {$this->_data['DiscountPercentage'] = (float)$discount; return $this;}
    public function setDiscountAmount($discount) {$this->_data['DiscountAmount'] = (float)$discount; return $this;}

    public function extractFromWcCoupon($coupon) {
        $this->setId($coupon->id);
        $this->setName($coupon->code);
        if(strpos($coupon->discount_type, 'percent') !== false) {
            $this->setUsePercentage(true);
        } else {
            $this->setUsePercentage(false);
        }
        $this->setDiscountPercentage($coupon->coupon_amount);
        $this->setDiscountAmount($coupon->coupon_amount);

        return $this;
    }
}
