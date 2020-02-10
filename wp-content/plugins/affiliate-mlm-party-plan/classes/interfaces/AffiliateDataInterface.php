<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface AffiliateDataInterface {
    /**
     * Affiliate Id
     *
     * @return int
     */
    public function getId();

    /**
     * Affiliate Address
     *
     * @return \Unisho\Jb\AddressDataInterface
     */
    public function getAddress();

    /**
     * Affiliate deleted flag
     *
     * @return bool
     */
    public function getDeleted();

    /**
     * Affiliate active flag
     *
     * @return bool
     */
    public function getActive();

    /**
     * Set the affiliate id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set the affiliate address
     *
     * @param \Unisho\Jb\AddressDataInterface $address
     * @return $this
     */
    public function setAddress($address);

    /**
     * Set the affiliate deleted flag
     *
     * @param bool $deleted
     * @return $this
     */
    public function setDeleted($deleted);

    /**
     * Set the active flag
     *
     * @param bool $active
     * @return $this
     */
    public function setActive($active);
}
