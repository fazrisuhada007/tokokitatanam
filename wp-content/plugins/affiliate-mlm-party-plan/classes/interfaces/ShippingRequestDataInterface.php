<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface ShippingRequestDataInterface {
    /**
     * Customer Data (including addresses)
     *
     * @return \Unisho\Jb\CustomerDataInterface
     */
    public function getCustomer();

    /**
     * Shipment TrackingNumber
     *
     * @return \Unisho\Jb\OrderProductVariantDataInterface[]
     */
    public function getProducts();

    /**
     * Shipment weight
     *
     * @return string
     */
    public function getShippingRateName();

    /**
     * Set the Customer (with the addresses)
     *
     * @param \Unisho\Jb\CustomerDataInterface $customer
     * @return $this
     */
    public function setCustomer($customer);

    /**
     * Set the Products
     *
     * @param \Unisho\Jb\OrderProductVariantDataInterface[] $products
     * @return $this
     */
    public function setProducts($products);

    /**
     * Set the shipping rate name
     *
     * @param string $rate_name
     * @return $this
     */
    public function setShippingRateName($rate_name);
}
