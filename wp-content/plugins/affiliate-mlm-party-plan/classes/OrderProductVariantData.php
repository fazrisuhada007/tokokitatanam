<?php

namespace Unisho\Sb;

class OrderProductVariantData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\OrderProductVariantDataInterface
{
	protected $_data = array(
		'ProductVariantId' => '',
		'Quantity' => null,
		'VariationId' => 0,
		'AttributeCost' => 0,
		'Price' => 0,
		'Attributes' => array(),
	);

	public function getProductVariantId()              {return $this->_data['ProductVariantId'];}
	public function getQuantity()                      {return $this->_data['Quantity'];}
	public function getVariationId()                   {return $this->_data['VariationId'];}
	public function getAttributeCost()                 {return $this->_data['AttributeCost'];}
	public function getPrice()                         {return $this->_data['Price'];}
	public function getAttributes()                    {return $this->_data['Attributes'];}

	public function setProductVariantId($product_id)   {$this->_data['ProductVariantId'] = (int)$product_id; return $this;}
	public function setQuantity($qty)                  {$this->_data['Quantity'] = (int)$qty; return $this;}
	public function setVariationId($vid)               {$this->_data['VariationId'] = (int)$vid; return $this;}
	public function setAttributeCost($cost)            {$this->_data['AttributeCost'] = (float)$cost; return $this;}
	public function setPrice($price)                   {$this->_data['Price'] = (float)$price; return $this;}
	public function setAttributes($attributes)         {$this->_data['Attributes'] = $attributes; return $this;}
}
