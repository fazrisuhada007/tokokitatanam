<?php

namespace Unisho\Sb;

class CustomerData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\CustomerDataInterface
{
	protected $_data = array(
		'CustomerId' => 0,					// int
		'UserName' => '',					// string
		'Email' => '',						// string
		'BillingAddress' => null,
		'ShippingAddress' => null,
		'AffiliateId' => 0
	);

	protected $_password = false;

	public function getCustomerId()			{return $this->_data['CustomerId'];}
	public function getUserName()			{return $this->_data['UserName'];}
	public function getEmail()				{return $this->_data['Email'];}
	public function getBillingAddress()		{return $this->_data['BillingAddress'];}
	public function getShippingAddress()	{return $this->_data['ShippingAddress'];}
	public function getAffiliateId()		{return $this->_data['AffiliateId'];}
	public function getPassword()			{return $this->_password;}

	public function setCustomerId($id)		{$this->_data['CustomerId'] = (int)$id; return $this;}
	public function setUserName($username)	{$this->_data['UserName'] = (string)$username; return $this;}
	public function setEmail($email)		{$this->_data['Email'] = (string)$email; return $this;}
	public function setBillingAddress($a)	{$this->_data['BillingAddress'] = $a; return $this;}
	public function setShippingAddress($a)	{$this->_data['ShippingAddress'] = $a; return $this;}
	public function setAffiliateId($a)		{$this->_data['AffiliateId'] = $a; return $this;}
	public function setPassword($p)			{$this->_password = (string)$p; return $this;}

	public function extractDataFromJson($json) {
		foreach($json as $k => $v) {
			if(array_key_exists($k, $this->_data)) {
				if('BillingAddress' == $k || 'ShippingAddress' == $k) {
					$address = new AddressData();
					$address->setData($v);
					// var_dump($v);
					// var_dump($address);
					if(false == $address->isEmpty()) {
						$this->setData($k, $address);
					}
				} else {
					$this->setData($k, $v);
				}
			} else {
				if('Password' == $k) {
					$this->setPassword($v);	
				}
			}
		}

		return $this;
	}

	public function extractDataFromWPUser($customer) {
		$this->setCustomerId($customer->ID);
		$this->setUserName($customer->user_login);
		$this->setEmail($customer->user_email);
		$this->setAffiliateId(get_user_meta($customer->ID, 'affiliate_id', true));
		$billing_address = new \Unisho\Sb\AddressData();
		$billing_address->extractFromUserBilling($customer);
		$shipping_address = new \Unisho\Sb\AddressData();
		$shipping_address->extractFromUserShipping($customer);
		if($billing_address->isEmpty() == false) {
			$this->setBillingAddress($billing_address);
		}
		if($shipping_address->isEmpty() == false) {
			$this->setShippingAddress($shipping_address);
		}

		return $this;
	}
}
