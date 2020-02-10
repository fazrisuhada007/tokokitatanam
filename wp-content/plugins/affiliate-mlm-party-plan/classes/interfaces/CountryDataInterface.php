<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface CountryDataInterface {
    /**
     * Country ID (code)
     *
     * @return string
     */
    public function getCountryId();

    /**
     * Country Name
     *
     * @return string
     */
    public function getName();

    /**
     * Country Three Letter ISO Code
     *
     * @return string
     */
    public function getThreeLetterIsoCode();

    /**
     * Set the Country Id (code)
     *
     * @param string $country_id
     * @return $this
     */
    public function setCountryId($country_id);

    /**
     * Set the Country Name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Set the Country Three Letter ISO Code
     *
     * @param string $c
     * @return $this
     */
    public function setThreeLetterIsoCode($c);
}
