<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface OrderShipmentDataResultInterface {
    /**
     * Order ID
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Order CustomerId
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Order shipping method
     *
     * @return string
     */
    public function getShippingMethod();

    /**
     * Order shipping address
     *
     * @return \Unisho\Jb\AddressDataInterface|null
     */
    public function getShippingAddress();

    /**
     * Order shipments (if any)
     *
     * @return \Unisho\Jb\ShipmentDataInterface[]
     */
    public function getShipments();

    /**
     * Set the Order Id
     *
     * @param string $order_id
     * @return $this
     */
    public function setOrderId($order_id);

    /**
     * Set the Customer Id
     *
     * @param int $customer_id
     * @return $this
     */
    public function setCustomerId($customer_id);

    /**
     * Set the shipping method
     *
     * @param string $shipping_method
     * @return $this
     */
    public function setShippingMethod($shipping_method);

    /**
     * Set the shipping addresss
     *
     * @param \Unisho\Jb\AddressDataInterface|null $address
     * @return $this
     */
    public function setShippingAddress($address);

    /**
     * Set the shipments
     *
     * @param \Unisho\Jb\ShipmentDataInterface[] $shipments
     * @return $this
     */
    public function setShipments($shipments);
}
