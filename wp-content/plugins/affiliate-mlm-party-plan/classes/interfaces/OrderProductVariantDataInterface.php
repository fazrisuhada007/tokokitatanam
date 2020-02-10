<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface OrderProductVariantDataInterface {
    /**
     * Product id
     *
     * @return int
     */
    public function getProductVariantId();

    /**
     * Product quantity
     *
     * @return int
     */
    public function getQuantity();

    /**
     * Total attribute costs
     *
     * @return float
     */
    public function getAttributeCost();

    /**
     * Selected/used/filled product attributes
     *
     * @return \Unisho\Jb\ProductAttributeDataInterface[]
     */
    public function getAttributes();

    /**
     * Set the Product Id
     *
     * @param int $product_id
     * @return $this
     */
    public function setProductVariantId($product_id);

    /**
     * Set the quantity for this product
     *
     * @param int $qty
     * @return $this
     */
    public function setQuantity($qty);

    /**
     * Set the total attribute costs
     *
     * @param float $cost
     * @return $this
     */
    public function setAttributeCost($cost);

    /**
     * Set the selected/used/filled product attributes
     *
     * @param \Unisho\Jb\ProductAttributeDataInterface[]
     * @return $this
     */
    public function setAttributes($attributes);
}
