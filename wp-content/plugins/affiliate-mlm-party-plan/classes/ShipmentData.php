<?php

namespace Unisho\Sb\Model;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ShipmentData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\Api\Data\ShipmentDataInterface
{
    protected $_data = array(
        'ShipmentId' => 0,
        'TrackingNumber' => '',
        'TotalWeight' => 0.0,
        'DateShipped' => '',
        'DateDelivered' => '',
    );

    public function getShipmentId() {return $this->_data['ShipmentId'];}
    public function getTrackingNumber() {return $this->_data['TrackingNumber'];}
    public function getTotalWeight() {return $this->_data['TotalWeight'];}
    public function getDateShipped() {return $this->_data['DateShipped'];}
    public function getDateDelivered() {return $this->_data['DateDelivered'];}

    public function setShipmentId($shipment_id) {$this->_data['ShipmentId'] = (int)$shipment_id; return $this;}
    public function setTrackingNumber($tracking_number) {$this->_data['TrackingNumber'] = (string)$tracking_number; return $this;}
    public function setTotalWeight($weight) {$this->_data['TotalWeight'] = (float)$weight; return $this;}
    public function setDateShipped($date_shipped) {$this->_data['DateShipped'] = $date_shipped; return $this;}
    public function setDateDelivered($date_delivered) {$this->_data['DateDelivered'] = $date_delivered; return $this;}
}
