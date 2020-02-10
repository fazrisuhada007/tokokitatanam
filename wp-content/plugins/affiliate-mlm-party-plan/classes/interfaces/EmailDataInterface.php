<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface EmailDataInterface {
    /**
     * From email
     *
     * @return string
     */
    public function getFrom();

    /**
     * From email name
     *
     * @return string
     */
    public function getFromName();

    /**
     * To email
     *
     * @return string
     */
    public function getTo();

    /**
     * To email name
     *
     * @return string
     */
    public function getToName();

    /**
     * Email cc
     *
     * @return string
     */
    public function getCc();

    /**
     * Email bcc
     *
     * @return string
     */
    public function getBcc();

    /**
     * Email subject
     *
     * @return string
     */
    public function getSubject();

    /**
     * Email body
     *
     * @return string
     */
    public function getBody();

    /**
     * Set from email
     *
     * @param string $s
     * @return $this
     */
    public function setFrom($s);

    /**
     * Set from email name
     *
     * @param string $s
     * @return $this
     */
    public function setFromName($s);

    /**
     * Set to email
     *
     * @param string $s
     * @return $this
     */
    public function setTo($s);

    /**
     * Set to email name
     *
     * @param string $s
     * @return $this
     */
    public function setToName($s);

    /**
     * Set cc
     *
     * @param string $s
     * @return $this
     */
    public function setCc($s);

    /**
     * Set email bcc
     *
     * @param string $s
     * @return $this
     */
    public function setBcc($s);

    /**
     * Set email subject
     *
     * @param string $s
     * @return $this
     */
    public function setSubject($s);

    /**
     * Set email body
     *
     * @param string $s
     * @return $this
     */
    public function setBody($s);
}
