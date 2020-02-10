<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface ShipmentDataInterface {
    /**
     * Shipment ID
     *
     * @return string
     */
    public function getShipmentId();

    /**
     * Shipment TrackingNumber
     *
     * @return string
     */
    public function getTrackingNumber();

    /**
     * Shipment weight
     *
     * @return float
     */
    public function getTotalWeight();

    /**
     * Shipping start date
     *
     * @return string|null
     */
    public function getDateShipped();

    /**
     * Shipment end date
     *
     * @return \Unisho\Jb\ShipmentDataInterface[]
     */
    public function getDateDelivered();

    /**
     * Set the Shipping  Id
     *
     * @param int $shipment_id
     * @return $this
     */
    public function setShipmentId($shipment_id);

    /**
     * Set the TrackingNumber
     *
     * @param string $tracking_number
     * @return $this
     */
    public function setTrackingNumber($tracking_number);

    /**
     * Set the shipping weight
     *
     * @param float $weight
     * @return $this
     */
    public function setTotalWeight($weight);

    /**
     * Set the shipping start date
     *
     * @param string $time
     * @return $this
     */
    public function setDateShipped($time);

    /**
     * Set the shipping end date
     *
     * @param string $time
     * @return $this
     */
    public function setDateDelivered($time);
}
