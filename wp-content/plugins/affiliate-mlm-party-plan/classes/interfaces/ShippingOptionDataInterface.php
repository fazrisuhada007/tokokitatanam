<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface ShippingOptionDataInterface {
    /**
     * Shipping Rate
     *
     * @return float
     */
    public function getRate();

    /**
     * Shipping Name
     *
     * @return string
     */
    public function getName();

    /**
     * Shipping Description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set the Shipping Rate
     *
     * @param float $rate
     * @return $this
     */
    public function setRate($rate);

    /**
     * Set the Shipping Name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Set the shipping description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);
}
