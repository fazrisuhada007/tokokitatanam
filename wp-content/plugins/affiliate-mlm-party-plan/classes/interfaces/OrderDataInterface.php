<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface OrderDataInterface {
    /**
     * Order ID
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Order Customer
     *
     * @return \Unisho\Jb\CustomerDataInterface
     */
    public function getCustomer();

    /**
     * Order products
     *
     * @return \Unisho\Jb\OrderProductVariantDataInterface[]
     */
    public function getOrderProductVariants();

    /**
     * Order status
     *
     * @return int
     */
    public function getOrderStatus();

    /**
     * Shipping status
     *
     * @return int
     */
    public function getShippingStatus();

    /**
     * Payment status
     *
     * @return int
     */
    public function getPaymentStatus();

    /**
     * Order tax amount
     *
     * @return float
     */
    public function getTaxAmount();

    /**
     * Order shipping amount
     *
     * @return float
     */
    public function getShippingAmount();

    /**
     * Order subtotal amount
     *
     * @return float
     */
    public function getSubtotalAmount();

    /**
     * Order total discount amount
     *
     * @return float
     */
    public function getDiscountAmount();

    /**
     * Order payment method name
     *
     * @return string
     */
    public function getPaymentMethodSystemName();

    /**
     * Order shipping method name
     *
     * @return string
     */
    public function getShippingRateName();

    /**
     * Order currency code
     *
     * @return string
     */
    public function getCustomerCurrencyCode();

    /**
     * Order affiliate id
     *
     * @return int
     */
    public function getAffiliateId();

    /**
     * Order customer IP
     *
     * @return string
     */
    public function getCustomerIp();

    /**
     * Order allow storing of the credit card number flag
     *
     * @return bool
     */
    public function getAllowStoringCreditCardNumber();

    /**
     * Order payment card type
     *
     * @return string
     */
    public function getCardType();

    /**
     * Order payment card owner name
     *
     * @return string
     */
    public function getCardName();

    /**
     * Order payment card number
     *
     * @return string
     */
    public function getCardNumber();

    /**
     * Order payment card verification value (CVV/CVV2/CSC)
     *
     * @return string
     */
    public function getCardCvv2();

    /**
     * Order payment masked card number
     *
     * @return string
     */
    public function getMaskedCreditCardNumber();

    /**
     * Order payment card expiry month (01-12)
     *
     * @return string
     */
    public function getCardExpirationMonth();

    /**
     * Order payment card expiry year (YY)
     *
     * @return string
     */
    public function getCardExpirationYear();

    /**
     * Order payment authorization transaction id
     *
     * @return string
     */
    public function getAuthorizationTransactionId();

    /**
     * Order payment authorization transaction code
     *
     * @return string
     */
    public function getAuthorizationTransactionCode();

    /**
     * Order payment authorization transaction result
     *
     * @return string
     */
    public function getAuthorizationTransactionResult();

    /**
     * Order store id (defaults to 1)
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Order flag for sending the confirmation email
     *
     * @return bool
     */
    public function getSendConfirmationEmailFlag();

    /**
     * Order coupon code (if any)
     *
     * @return string
     */
    public function getCouponCode();

    /**
     * Set the Order Id
     *
     * @param string $order_id
     * @return $this
     */
    public function setOrderId($order_id);

    /**
     * Set the Customer (which also has the addresses in it)
     *
     * @param \Unisho\Jb\CustomerDataInterface $customer
     * @return $this
     */
    public function setCustomer($customer);

    /**
     * Set the order products
     *
     * @param \Unisho\Jb\OrderProductVariantDataInterface[] $products
     * @return $this
     */
    public function setOrderProductVariants($products);

    /**
     * Set the order status
     *
     * @param int $status
     * @return $this
     */
    public function setOrderStatus($status);

    /**
     * Set the shipping status
     *
     * @param int $status
     * @return $this
     */
    public function setShippingStatus($status);

    /**
     * Set the payment status
     *
     * @param int $status
     * @return $this
     */
    public function setPaymentStatus($status);

    /**
     * Set the order tax amount
     *
     * @param float $tax
     * @return $this
     */
    public function setTaxAmount($tax);

    /**
     * Set the order shipping amount
     *
     * @param float $shipping
     * @return $this
     */
    public function setShippingAmount($shipping);

    /**
     * Set the order subtotal amount
     *
     * @param float $subtotal
     * @return $this
     */
    public function setSubtotalAmount($subtotal);

    /**
     * Set the order discount amount
     *
     * @param float $discount
     * @return $this
     */
    public function setDiscountAmount($discount);

    /**
     * Set the order payment method name
     *
     * @param string $payment
     * @return $this
     */
    public function setPaymentMethodSystemName($payment);

    /**
     * Set the order shipping method name
     *
     * @param string $shipping
     * @return $this
     */
    public function setShippingRateName($shipping);

    /**
     * Set the order currency code
     *
     * @param string $code
     * @return $this
     */
    public function setCustomerCurrencyCode($code);

    /**
     * Set the order affiliate id
     *
     * @param int $affiliate_id
     * @return $this
     */
    public function setAffiliateId($affiliate_id);

    /**
     * Set the order customer's IP from which it placed the order from
     *
     * @param string $ip
     * @return $this
     */
    public function setCustomerIp($ip);

    /**
     * Set the order allow storing of the credit card number flag
     *
     * @param bool $allow
     * @return $this
     */
    public function setAllowStoringCreditCardNumber($allow);

    /**
     * Set the order payment card type
     *
     * @param string $card_type
     * @return $this
     */
    public function setCardType($card_type);

    /**
     * Set the order payment name on card
     *
     * @param string $card_name
     * @return $this
     */
    public function setCardName($card_name);

    /**
     * Set the order payment card number
     *
     * @param string $card_number
     * @return $this
     */
    public function setCardNumber($card_number);

    /**
     * Set the order payment card masked number
     *
     * @param string $card_number
     * @return $this
     */
    public function setMaskedCreditCardNumber($card_number);

    /**
     * Set the order payment card verification value (CVV/CVV2/CSC)
     *
     * @param string $cvv
     * @return $this
     */
    public function setCardCvv2($cvv);

    /**
     * Set the order payment card expiry month (01-12)
     *
     * @param string $month
     * @return $this
     */
    public function setCardExpirationMonth($month);

    /**
     * Set the order payment card expiry year (YY form)
     *
     * @param string $year
     * @return $this
     */
    public function setCardExpirationYear($year);

    /**
     * Set the order authorization transaction id
     *
     * @param string $tid
     * @return $this
     */
    public function setAuthorizationTransactionId($tid);

    /**
     * Set the order authorization transaction code
     *
     * @param string $tcode
     * @return $this
     */
    public function setAuthorizationTransactionCode($tcode);

    /**
     * Set the order authorization transaction result
     *
     * @param string $tres
     * @return $this
     */
    public function setAuthorizationTransactionResult($tres);

    /**
     * Set the order store id
     *
     * @param int $store_id
     * @return $this
     */
    public function setStoreId($store_id);

    /**
     * Set the order flag to send the confirmation email
     *
     * @param bool $flag
     * @return $this
     */
    public function setSendConfirmationEmailFlag($flag);

    /**
     * Set the order coupon code
     *
     * @param string $code
     * @return $this
     */
    public function setCouponCode($code);
}
