<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface StateDataInterface {
    /**
     * State ID (code)
     *
     * @return string
     */
    public function getStateId();

    /**
     * State Code
     *
     * @return string
     */
    public function getStateCode();

    /**
     * State Name
     *
     * @return string
     */
    public function getName();

    /**
     * Country ID (code)
     *
     * @return string
     */
    public function getCountryId();

    /**
     * Set the State Id (code)
     *
     * @param string $state_id
     * @return $this
     */
    public function setStateId($state_id);

    /**
     * Set the State Code
     *
     * @param string $state_code
     * @return $this
     */
    public function setStateCode($state_code);

    /**
     * Set the State Name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Set the Country Id (code)
     *
     * @param string $country_id
     * @return $this
     */
    public function setCountryId($country_id);
}
