<?php

namespace Unisho\Sb;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomerAccess extends \Unisho\Sb\DataStruct implements \Unisho\Sb\CustomerAccessInterface
{
	protected $_data = array(
		'Username' => '',
		'Password' => '',
	);

	public function getUsername() {return $this->_data['Username'];}
	public function getPassword() {return $this->_data['Password'];}

	public function setUsername($username) {$this->_data['Username'] = $username; return $this;}
	public function setPassword($password) {$this->_data['Password'] = $password; return $this;}
}
