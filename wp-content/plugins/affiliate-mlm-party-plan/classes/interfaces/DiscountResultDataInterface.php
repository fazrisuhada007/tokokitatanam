<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface DiscountResultDataInterface {
    /**
     * Discount (coupon) id
     *
     * @return int
     */
    public function getId();

    /**
     * Coupon name/code
     *
     * @return string
     */
    public function getCoupon();

    /**
     * if the coupon is (still) usable
     *
     * @return bool
     */
    public function getSuccess();

    /**
     * discount data
     *
     * @return \Unisho\Jb\DiscountDataInterface
     */
    public function getDiscount();

    /**
     * Set the discount id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set the coupon name
     *
     * @param string $name
     * @return $this
     */
    public function setCoupon($coupon);

    /**
     * Set the success (can be used) flag
     *
     * @param bool $success
     * @return $this
     */
    public function setSuccess($success);

    /**
     * Set the discount data
     *
     * @param \Unisho\Jb\DiscountDataInterface $discount
     * @return $this
     */
    public function setDiscount($discount);
}
