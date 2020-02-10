<?php

namespace Unisho\Sb;

class StateData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\StateDataInterface
{
	protected $_data = array(
		'StateId' => '',
		'StateCode' => '',
		'Name' => '',
		'CountryId' => ''
	);

	public function getStateId()						{return $this->_data['StateId'];}
	public function getStateCode()						{return $this->_data['StateCode'];}
	public function getName()							{return $this->_data['Name'];}
	public function getCountryId()						{return $this->_data['CountryId'];}

	public function setStateId($state_id)			{$this->_data['StateId'] = (string)$state_id; return $this;}
	public function setStateCode($state_code)		{$this->_data['StateCode'] = (string)$state_code; return $this;}
	public function setName($name)					{$this->_data['Name'] = (string)$name; return $this;}
	public function setCountryId($country_id)		{$this->_data['CountryId'] = (string)$country_id; return $this;}
}
