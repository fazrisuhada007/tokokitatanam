<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface DiscountDataInterface {
    /**
     * Discount (coupon) id
     *
     * @return int
     */
    public function getId();

    /**
     * Discount name
     *
     * @return string
     */
    public function getName();

    /**
     * use percentage flag
     *
     * @return bool
     */
    public function getUsePercentage();

    /**
     * discount percent
     *
     * @return float
     */
    public function getDiscountPercentage();

    /**
     * discount amount (flat)
     *
     * @return float
     */
    public function getDiscountAmount();

    /**
     * Set the discount id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set the discount name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Set the use percentage flag
     *
     * @param bool $use_percentage
     * @return $this
     */
    public function setUsePercentage($use_percentage);

    /**
     * Set the discount percentage
     *
     * @param float $discount
     * @return $this
     */
    public function setDiscountPercentage($discount);

    /**
     * Set the discount amount
     *
     * @param float $discount
     * @return $this
     */
    public function setDiscountAmount($discount);
}
