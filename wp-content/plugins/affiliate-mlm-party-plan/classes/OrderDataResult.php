<?php

namespace Unisho\Sb;

class OrderDataResult extends \Unisho\Sb\DataStruct implements \Unisho\Sb\OrderDataResultInterface
{
	protected $_data = array(
		'OrderId' => '',
		'OrderGuid' => '',
		'CustomerIP' => '0.0.0.0',
		'AffiliateId' => 0,
		'CustomerId' => 0,
		'Customer' => null,
		'PaymentMethod' => '',
		'OrderStatus' => '',
		'OrderProductVariants' => null,
		'OrderDiscounts' => null,
        'OrderTotal' => 0.0,
        'OrderTax' => 0.0,
        'OrderShipping' => 0.0,
		'OrderSubtotalExclTax' => 0.0,
		'OrderDiscount' => 0.0,
		'CreatedOnUtc' => '1970-01-01 00:00:00',
		'Deleted' => false
	);

	protected $_wc_order = null;

	public function __construct($order_id = null) {
		if($order_id) {
			$this->extractFromWcOrder($order_id);
		}

		return $this;
	}

	public function getOrderId()					{return $this->_data['OrderId'];}
	public function getOrderGuid()					{return $this->_data['OrderGuid'];}
	public function getCustomerIP()					{return $this->_data['CustomerIP'];}
	public function getAffiliateId()				{return $this->_data['AffiliateId'];}
	public function getCustomerId()					{return $this->_data['CustomerId'];}
	public function getCustomer()					{return $this->_data['Customer'];}
	public function getPaymentMethod()				{return $this->_data['PaymentMethod'];}
	public function getOrderStatus()				{return $this->_data['OrderStatus'];}
	public function getOrderProductVariants()		{return $this->_data['OrderProductVariants'];}
	public function getOrderDiscounts()				{return $this->_data['OrderDiscounts'];}
    public function getOrderTotal()					{return $this->_data['OrderTotal'];}
    public function getOrderTax()					{return $this->_data['OrderTax'];} 
    public function getOrderShipping()				{return $this->_data['OrderShipping'];}
	public function getOrderSubtotalExclTax()		{return $this->_data['OrderSubtotalExclTax'];}
	public function getOrderDiscount()				{return $this->_data['OrderDiscount'];}
	public function getCreatedOnUtc()				{return $this->_data['CreatedOnUtc'];}
	public function getDeleted()					{return $this->_data['Deleted'];}

	public function setOrderId($order_id)			{$this->_data['OrderId'] = (int)$order_id; return $this;}
	public function setOrderGuid($order_guid)		{$this->_data['OrderGuid'] = (string)$order_guid; return $this;}
	public function setCustomerIP($ip)				{$this->_data['CustomerIP'] = (string)$ip; return $this;}
	public function setAffiliateId($aff_id)			{$this->_data['AffiliateId'] = (string)$aff_id; return $this;}
	public function setCustomerId($cid)				{$this->_data['CustomerId'] = (string)$cid; return $this;}
	public function setCustomer($customer)			{$this->_data['Customer'] = $customer; return $this;}
	public function setPaymentMethod($pay_method)	{$this->_data['PaymentMethod'] = $pay_method; return $this;}
	public function setOrderStatus($order_status)	{$this->_data['OrderStatus'] = $order_status; return $this;}
	public function setOrderProductVariants($p)		{$this->_data['OrderProductVariants'] = $p; return $this;}
	public function setOrderDiscounts($d)			{$this->_data['OrderDiscounts'] = $d; return $this;}
    public function setOrderTotal($total)			{$this->_data['OrderTotal'] = wc_format_decimal((float)$total, 7); return $this;}
    public function setOrderTax($tax)			    {$this->_data['OrderTax'] = wc_format_decimal((float)$tax, 7); return $this;}
    public function setOrderShipping($shipping)		{$this->_data['OrderShipping'] = wc_format_decimal((float)$shipping, 7); return $this;}
	public function setOrderSubtotalExclTax($subtotal)	{$this->_data['OrderSubtotalExclTax'] = wc_format_decimal((float)$subtotal, 7); return $this;}
	public function setOrderDiscount($discount)		{$this->_data['OrderDiscount'] = wc_format_decimal((float)$discount, 7); return $this;}
	public function setCreatedOnUtc($created)		{$this->_data['CreatedOnUtc'] = $created; return $this;}
	public function setDeleted($deleted)			{$this->_data['Deleted'] = (bool)$deleted; return $this;}

	public function getWCOrder() {return $this->_wc_order;}
	public function setWCOrder($order) {$this->_wc_order = $order; return $this;}

	public function extractFromWcOrder($order_id) {
		/*
		'OrderId' => '',
		'CustomerIP' => '0.0.0.0',
		'AffiliateId' => 0,
		'CustomerId' => 0,
		'Customer' => null,
		'PaymentMethod' => '',
		'OrderStatus' => '',
		'OrderProductVariants' => null,   // OrderProductVariantData[]
		'OrderDiscounts' => null,         // OrderDiscountData[] 
		'OrderTotal' => 0.0,
		'OrderSubtotalExclTax' => 0.0,
		'OrderDiscount' => 0.0,
		'CreatedOnUtc' => '1970-01-01 00:00:00',
		'Deleted' => false
		*/
		$order_factory = new \WC_Order_Factory();
		$order = $order_factory->get_order($order_id);
		$post = $order->post;
		if($post->post_type != 'shop_order') {
			return $this;
		}
		$this->setWCOrder($order);

		if( $order->get_used_coupons() ) {
			global $wpdb;
			$table = $wpdb->prefix . "woocommerce_order_items";
			$order_items = $wpdb->get_results( $wpdb->prepare( "SELECT  *  FROM  $table  WHERE  order_id = %d ", $order_id ) );
			$OrderDiscounts = array();
			foreach( $order_items as $item_values ){
				if( 'coupon' == $item_values->order_item_type ){
					$item_id = $item_values->order_item_id;
					$coupon_name = $item_values->order_item_name;
					$order_discount_amount = wc_get_order_item_meta( $item_id, 'discount_amount', true );
					$order_discount_tax_amount = wc_get_order_item_meta( $item_id, 'discount_amount_tax', true );
					if($order_discount_amount){
						$order_discount_amount = floatval($order_discount_amount);
					}
					if($order_discount_tax_amount){
						$order_discount_tax_amount = floatval($order_discount_tax_amount);
					}
					$coupon_details = array();
					$coupon_details['DiscountId'] = intval($item_id);
					$coupon_details['CouponCode'] = $coupon_name;
					$coupon_details['discount_amount'] = $order_discount_amount;
					$coupon_details['discount_amount_tax'] = $order_discount_tax_amount;
					$OrderDiscounts[] = $coupon_details;
				}
			}
			$this->setOrderDiscounts($OrderDiscounts);
		}
		$total = $order->get_total();
		$tax = $order->get_total_tax();
		$shipping = $order->get_total_shipping();	
		$paymethod = $order->payment_method_title;
		$stat = $order->get_status();
		
		$this->setOrderId($post->ID);
		$this->setCustomerIP($post->_customer_ip_address);
		$this->setCustomerId($post->_customer_user);
		$this->setAffiliateId((int)$post->affiliate_id);		
		$this->setPaymentMethod($paymethod);
		$this->setOrderStatus($stat);
		
        $this->setOrderTotal($total);
        $this->setOrderTax($tax);
        $this->setOrderShipping($shipping);

		$this->setOrderDiscount($post->_cart_discount);
		$this->setOrderSubtotalExclTax($total - $tax - $shipping);
		$this->setCreatedOnUtc($post->post_date_gmt);
		if($post->post_status == 'trash') {
			$this->setDeleted(true);
		} else {
			$this->setDeleted(false);
		}
		$customer = new CustomerData();
		$customer_fields = array(
			'CustomerId' => $post->_customer_user
		);
		$billing_fields = sb_extract_address_fields($post, 'billing');
		$shipping_fields = sb_extract_address_fields($post, 'shipping');
		if($post->_customer_user) {
			$user = get_user_by('ID', $post->_customer_user);
			$customer_fields['UserName'] = $user->user_login;
			$customer_fields['Email'] = $user->user_email;
			$customer_fields['AffiliateId'] = $user->_affiliate_id;
		} else {
			if($billing_fields['Email'] != '') {
				$customer_fields['Email'] = $billing_fields['Email'];
			} elseif($shipping_fields['Email'] != '') {
				$customer_fields['Email'] = $shipping_fields['Email'];
			}
		}
		$customer_fields['BillingAddress'] = $billing_fields;
		$customer_fields['ShippingAddress'] = $shipping_fields;
		$customer->extractDataFromJson($customer_fields);
		$this->setCustomer($customer);
		
		/*
		'ProductVariantId' => '',
		'Quantity' => null,
		'AttributeCost' => 0,
		'Attributes' => array(),
		*/
		/*
		$orderProductVariantData = array(
			'ProductVariantId' => 12, // product_id
			'Quantity' => 2,
			'VariationId' => 3, // 0 if the product is simple
			'Price' => 25,
			'Attributes => array(
				array(
					'AttributeId' => 1, // Color
					'AttributeValue' => 'White' // or maybe array('White')
				),
				array(
					'AttributeId' => 2,
					'AttributeValue' => 'Large'
				),
			),
		);
		*/
		$items = $order->get_items('line_item');
		$items_r = array();
		foreach($items as $item) {
			$item_r = new OrderProductVariantData();
			$item_r->setProductVariantId($item['product_id']);
			$item_r->setVariationId($item['variation_id']);
			$item_r->setPrice($item['line_total']);
			$item_r->setQuantity($item['qty']);
			$product = new ProductVariant();
			$product_attributes = $product->extractAttributesFromProductId($item['product_id']);
			$attributes = array();
			foreach($product_attributes as $attr) {
				$attributes[] = $attr['Id'];
			}
			$attributes_used = array();
			foreach($attributes as $attr) {
				if($item[$attr]) {
					$attributes_used[] = array(
						'AttributeId' => $attr,
						'AttributeValue' => array($item[$attr]),
					);
				}
			}
			$item_r->setAttributes($attributes_used);
			$items_r[] = $item_r;
		}
		$this->setOrderProductVariants($items_r);

		return $this;
	}
}
