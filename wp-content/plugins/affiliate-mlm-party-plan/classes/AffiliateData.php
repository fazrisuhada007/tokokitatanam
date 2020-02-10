<?php

namespace Unisho\Sb;

class AffiliateData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\AffiliateDataInterface
{
	protected $_data = array(
		'Id' => '',
		'Address' => null,
		'Deleted' => false,
		'Active' => false,
	);

	public function getId()						{return $this->_data['Id'];}
	public function getAddress()				{return $this->_data['Address'];}
	public function getDeleted()				{return $this->_data['Deleted'];}
	public function getActive()					{return $this->_data['Active'];}

	public function setId($id)					{$this->_data['Id'] = (int)$id; return $this;}
	public function setAddress($address)		{$this->_data['Address'] = $address; return $this;}
	public function setDeleted($deleted)		{$this->_data['Deleted'] = (bool)$delete; return $this;}
	public function setActive($active)			{$this->_data['Active'] = (bool)$active; return $this;}
}
