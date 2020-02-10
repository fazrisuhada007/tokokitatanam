<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface OrderNoteRequestInterface {
    /**
     * Order Id
     *
     * @return int
     */
    public function getOrderId();

    /**
     * The note to be added
     *
     * @return string
     */
    public function getNote();

    /**
     * Send email to customer with the note
     *
     * @return bool
     */
    public function getNotifyCustomer();

    /**
     * Note visible on the frontend flag
     *
     * @return bool
     */
    public function getDisplayToCustomer();

    /**
     * Set the order on which the note will be added
     *
     * @param int $order_id
     * @return $this
     */
    public function setOrderId($order_id);

    /**
     * Set the note text
     *
     * @param string $note
     * @return $this
     */
    public function setNote($note);

    /**
     * Set the notify customer flag. If set to yes, the customer will receive an email with the note
     *
     * @param bool $notify_customer
     * @return $this
     */
    public function setNotifyCustomer($notify_customer);

    /**
     * Set the visible on frontend flag
     *
     * @param bool $display
     * @return $this
     */
    public function setDisplayToCustomer($display);
}
