<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface CustomerDataInterface {
    /**
     * Customer id
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Customer username(email)
     *
     * @return string
     */
    public function getUserName();

    /**
     * Customer email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Customer shipping address
     *
     * @return \Unisho\Sb\AddressDataInterface|null
     */
    public function getShippingAddress();

    /**
     * Customer billing address
     *
     * @return \Unisho\Sb\AddressDataInterface|null
     */
    public function getBillingAddress();

    /**
     * Customer affiliate id
     *
     * @return int
     */
    public function getAffiliateId();

    /**
     * Customer password (as it was set earlier)
     *
     * @return string
     */
    public function getPassword();

    /**
     * Set the id
     *
     * @param int $id
     * @return $this
     */
    public function setCustomerId($id);

    /**
     * Set the username(email)
     *
     * @param string $username
     * @return $this
     */
    public function setUserName($username);

    /**
     * Set the email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * Set the billing address
     *
     * @param \Unisho\Sb\AddressDataInterface|null $a
     * @return $this
     */
    public function setBillingAddress($a);

    /**
     * Set the shipping address
     *
     * @param \Unisho\Sb\AddressDataInterface|null $a
     * @return $this
     */
    public function setShippingAddress($a);

    /**
     * Set the affiliate id
     *
     * @param int $a
     * @return $this
     */
    public function setAffiliateId($a);

    /**
     * Set the customer password (for new customers only)
     *
     * @param string $p
     * @return $this
     */
    public function setPassword($p);
}
