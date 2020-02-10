<?php

namespace Unisho\Sb;

class CountryData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\CountryDataInterface
{
	protected $_data = array(
		'CountryId' => '',
		'Name' => '',
		'ThreeLetterIsoCode' => ''
	);

	public function getCountryId()						{return $this->_data['CountryId'];}
	public function getName()							{return $this->_data['Name'];}
	public function getThreeLetterIsoCode()				{return $this->_data['ThreeLetterIsoCode'];}

	public function setCountryId($country_id)		{$this->_data['CountryId'] = (string)$country_id; return $this;}
	public function setName($name)					{$this->_data['Name'] = (string)$name; return $this;}
	public function setThreeLetterIsoCode($c)		{$this->_data['ThreeLetterIsoCode'] = (string)$c; return $this;}
}
