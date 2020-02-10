<?php

namespace Unisho\Sb;

class ProductAttributeData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\ProductAttributeDataInterface
{
	protected $_data = array(
		'AttributeId' => 0,
		'AttributeType' => '',
		'AttributesValue' => array(),
	);

	public function getAttributeId()                {return $this->_data['AttributeId'];}
	public function getAttributeType()              {return $this->_data['AttributeType'];}
	public function getAttributeValue()             {return $this->_data['AttributeValue'];}

	public function setAttributeId($attribute_id)   {$this->_data['AttributeId'] = (int)$attribute_id; return $this;}
	public function setAttributeType($type)         {$this->_data['AttributeType'] = (string)$type; return $this;}
	public function setAttributeValue($values)      {$this->_data['AttributeValue'] = $values; return $this;}
}
