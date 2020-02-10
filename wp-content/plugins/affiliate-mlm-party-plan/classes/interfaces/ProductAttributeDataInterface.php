<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface ProductAttributeDataInterface {
    /**
     * Attribute id
     *
     * @return int
     */
    public function getAttributeId();

    /**
     * Attribute type (configurable_attribute / custom_option)
     *
     * @return string
     */
    public function getAttributeType();

    /**
     * Attribute values
     *
     * @return string[]
     */
    public function getAttributeValue();

    /**
     * Set the Attribute Id
     *
     * @param int $attribute_id
     * @return $this
     */
    public function setAttributeId($attribute_id);

    /**
     * Set the Attribute Type (configurable_attribute / custom_option)
     *
     * @param string $type
     * @return $this
     */
    public function setAttributeType($type);

    /**
     * Set the attribute values
     *
     * @param string[] $values
     * @return $this
     */
    public function setAttributeValue($values);
}
