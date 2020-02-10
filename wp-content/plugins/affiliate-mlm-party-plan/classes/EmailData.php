<?php

namespace Unisho\Sb;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EmailData extends \Unisho\Sb\DataStruct implements \Unisho\Sb\EmailDataInterface
{
    protected $_data = array(
        'From' => '',
        'FromName' => '',
        'To' => '',
        'ToName' => '',
        'Cc' => '',
        'Bcc' => '',
        'Subject' => '',
        'Body' => '',
    );

    public function getFrom() {return $this->_data['From'];}
    public function getFromName() {return $this->_data['FromName'];}
    public function getTo() {return $this->_data['To'];}
    public function getToName() {return $this->_data['ToName'];}
    public function getCc() {return $this->_data['Cc'];}
    public function getBcc() {return $this->_data['Bcc'];}
    public function getSubject() {return $this->_data['Subject'];}
    public function getBody() {return $this->_data['Body'];}

    public function setFrom($s) {$this->_data['From'] = $s; return $this;}
    public function setFromName($s) {$this->_data['FromName'] = $s; return $this;}
    public function setTo($s) {$this->_data['To'] = $s; return $this;}
    public function setToName($s) {$this->_data['ToName'] = $s; return $this;}
    public function setCc($s) {$this->_data['Cc'] = $s; return $this;}
    public function setBcc($s) {$this->_data['Bcc'] = $s; return $this;}
    public function setSubject($s) {$this->_data['Subject'] = $s; return $this;}
    public function setBody($s) {$this->_data['Body'] = $s; return $this;}
}