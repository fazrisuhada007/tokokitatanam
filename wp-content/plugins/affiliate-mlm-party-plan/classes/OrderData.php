<?php

namespace Unisho\Sb\Model;

class OrderData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\OrderDataInterface
{
	protected $_data = array(
		'OrderId' => '',
		'Customer' => null,
		'OrderProductVariants' => array(),
		'OrderStatus' => 0,
		'ShippingStatus' => 0,
		'PaymentStatus' => 0,
		'TaxAmount' => 0.0,
		'ShippingAmount' => 0.0,
		'SubtotalAmount' => 0.0,
		'DiscountAmount' => 0.0,
		'PaymentMethodSystemName' => '',
		'ShippingRateName' => '',
		'CustomerCurrencyCode' => '',
		'AffiliateId' => 0,
		'CustomerIp' => '0.0.0.0',
		'AllowStoringCreditCardNumber' => false,
		'CardType' => '',
		'CardName' => '',
		'CardNumber' => '',
		'MaskedCreditCardNumber' => '',
		'CardCvv2' => '',
		'CardExpirationMonth' => '',
		'CardExpirationYear' => '',
		'AuthorizationTransactionId' => '',
		'AuthorizationTransactionCode' => '',
		'AuthorizationTransactionResult' => '',
		'StoreId' => 1,
		'SendConfirmationEmailFlag' => true,
		'CouponCode' => ''
	);

	public function getOrderId()							{return $this->_data['OrderId'];}
	public function getCustomer()							{return $this->_data['Customer'];}
	public function getOrderProductVariants()				{return $this->_data['OrderProductVariants'];}
	public function getOrderStatus()						{return $this->_data['OrderStatus'];}
	public function getShippingStatus()						{return $this->_data['ShippingStatus'];}
	public function getPaymentStatus()						{return $this->_data['PaymentStatus'];}
	public function getTaxAmount()							{return $this->_data['TaxAmount'];}
	public function getShippingAmount()						{return $this->_data['ShippingAmount'];}
	public function getSubtotalAmount()						{return $this->_data['SubtotalAmount'];}
	public function getDiscountAmount()						{return $this->_data['DiscountAmount'];}
	public function getPaymentMethodSystemName()			{return $this->_data['PaymentMethodSystemName'];}
	public function getShippingRateName()					{return $this->_data['ShippingRateName'];}
	public function getCustomerCurrencyCode()				{return $this->_data['CustomerCurrencyCode'];}
	public function getAffiliateId()						{return $this->_data['AffiliateId'];}
	public function getCustomerIp()							{return $this->_data['CustomerIp'];}
	public function getAllowStoringCreditCardNumber()		{return $this->_data['AllowStoringCreditCardNumber'];}
	public function getCardType()							{return $this->_data['CardType'];}
	public function getCardName()							{return $this->_data['CardName'];}
	public function getCardNumber()							{return $this->_data['CardNumber'];}
	public function getMaskedCreditCardNumber()				{return $this->_data['MaskedCreditCardNumber'];}
	public function getCardCvv2()							{return $this->_data['CardCvv2'];}
	public function getCardExpirationMonth()				{return $this->_data['CardExpirationMonth'];}
	public function getCardExpirationYear()					{return $this->_data['CardExpirationYear'];}
	public function getAuthorizationTransactionId()			{return $this->_data['AuthorizationTransactionId'];}
	public function getAuthorizationTransactionCode()		{return $this->_data['AuthorizationTransactionCode'];}
	public function getAuthorizationTransactionResult()		{return $this->_data['AuthorizationTransactionResult'];}
	public function getStoreId()							{return $this->_data['StoreId'];}
	public function getSendConfirmationEmailFlag()			{return $this->_data['SendConfirmationEmailFlag'];}
	public function getCouponCode()							{return $this->_data['CouponCode'];}

	public function setOrderId($order_id)						{$this->_data['OrderId'] = (string)$order_id; return $this;}
	public function setCustomer($customer)						{$this->_data['Customer'] = $customer; return $this;}
	public function setOrderProductVariants($products)			{$this->_data['OrderProductVariants'] = $products; return $this;}
	public function setOrderStatus($status)						{$this->_data['OrderStatus'] = (int)$status; return $this;}
	public function setShippingStatus($status)					{$this->_data['ShippingStatus'] = (int)$status; return $this;}
	public function setPaymentStatus($status)					{$this->_data['PaymentStatus'] = (int)$status; return $this;}
	public function setTaxAmount($tax)							{$this->_data['TaxAmount'] = (float)$tax; return $this;}
	public function setShippingAmount($shipping)				{$this->_data['ShippingAmount'] = (float)$shipping; return $this;}
	public function setSubtotalAmount($subtotal)				{$this->_data['SubtotalAmount'] = (float)$subtotal; return $this;}
	public function setDiscountAmount($discount)				{$this->_data['DiscountAmount'] = (float)$discount; return $this;}
	public function setPaymentMethodSystemName($payment)		{$this->_data['PaymentMethodSystemName'] = (string)$payment; return $this;}
	public function setShippingRateName($shipping)				{$this->_data['ShippingRateName'] = (string)$shipping; return $this;}
	public function setCustomerCurrencyCode($code)				{$this->_data['CustomerCurrencyCode'] = (string)$code; return $this;}
	public function setAffiliateId($affiliate_id)				{$this->_data['AffiliateId'] = (int)$affiliate_id; return $this;}
	public function setCustomerIp($ip)							{$this->_data['CustomerIp'] = (string)$ip; return $this;}
	public function setAllowStoringCreditCardNumber($allow)		{$this->_data['AllowStoringCreditCardNumber'] = (bool)$allow; return $this;}
	public function setCardType($card_type)						{$this->_data['CardType'] = (string)$card_type; return $this;}
	public function setCardName($card_name)						{$this->_data['CardName'] = (string)$card_name; return $this;}
	public function setCardNumber($card_number)					{$this->_data['CardNumber'] = (string)$card_number; return $this;}
	public function setMaskedCreditCardNumber($card_number)		{$this->_data['MaskedCreditCardNumber'] = (string)$card_number; return $this;}
	public function setCardCvv2($cvv)							{$this->_data['CardCvv2'] = (string)$cvv; return $this;}
	public function setCardExpirationMonth($month)				{$this->_data['CardExpirationMonth'] = (string)$month; return $this;}
	public function setCardExpirationYear($year)				{$this->_data['CardExpirationYear'] = (string)$year; return $this;}
	public function setAuthorizationTransactionId($tid)			{$this->_data['CardExpirationYear'] = (string)$tid; return $this;}
	public function setAuthorizationTransactionCode($tcode)		{$this->_data['CardExpirationYear'] = (string)$tcode; return $this;}
	public function setAuthorizationTransactionResult($tres)	{$this->_data['AuthorizationTransactionResult'] = (string)$tres; return $this;}
	public function setStoreId($store_id)						{$this->_data['StoreId'] = (int)$store_id; return $this;}
	public function setSendConfirmationEmailFlag($flag)			{$this->_data['SendConfirmationEmailFlag'] = (bool)$flag; return $this;}
	public function setCouponCode($code)						{$this->_data['CouponCode'] = $code; return $this;}
}
