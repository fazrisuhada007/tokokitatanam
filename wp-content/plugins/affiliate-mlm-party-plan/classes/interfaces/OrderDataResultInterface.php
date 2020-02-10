<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface OrderDataResultInterface {
    /**
     * Order ID
     *
     * @return int
     */
    public function getOrderId();

    /**
     * Order GUID (increment id)
     *
     * @return string
     */
    public function getOrderGuid();

    /**
     * Customer IP from which the order was placed
     *
     * @return string
     */
    public function getCustomerIP();

    /**
     * Payment Method from which the order was paid
     *
     * @return string
     */
    public function getPaymentMethod();

    /**
     * Order Status from which the order was placed
     *
     * @return string
     */
    public function getOrderStatus();

    /**
     * Affiliate Id (if any)
     *
     * @return int
     */
    public function getAffiliateId();

    /**
     * Customer Id
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Customer Data
     *
     * @return \Unisho\Jb\CustomerDataInterface
     */
    public function getCustomer();

    /**
     * Order products
     *
     * @return array
     */
    public function getOrderProductVariants();

    /**
     * Order discounts (if any)
     *
     * @return array
     */
    public function getOrderDiscounts();

    /**
     * Order total (aka grand total)
     *
     * @return float
     */
    public function getOrderTotal();

    /**
     * Order subtotal excluding tax
     *
     * @return float
     */
    public function getOrderSubtotalExclTax();

    /**
     * Order discount
     *
     * @return float
     */
    public function getOrderDiscount();

    /**
     * Order creation date (in UTC timezone)
     *
     * @return string
     */
    public function getCreatedOnUtc();

    /**
     * Order deleted flag 
     *
     * @return bool
     */
    public function getDeleted();

    /**
     * Set the Order Id
     *
     * @param string $order_id
     * @return $this
     */
    public function setOrderId($order_id);
}
