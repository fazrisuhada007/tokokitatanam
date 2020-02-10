<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface ProductVariantInterface {
    /**
     * Product id
     *
     * @return int
     */
    public function getId();

    /**
     * Product name
     *
     * @return string
     */
    public function getName();

    /**
     * Product description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Product sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Product price
     *
     * @return float
     */
    public function getPrice();

    /**
     * Product weight
     *
     * @return float
     */
    public function getWeight();

    /**
     * Product stock quantity level
     *
     * @return int
     */
    public function getStockQuantity();

    /**
     * Product has stock management
     *
     * @return bool
     */
    public function getManageInventory();

    /**
     * Product images info/data
     *
     * @return \Unisho\Sb\ProductVariantImageInterface[]
     */
    public function getImages();

    /**
     * Product attributes (frontend-chooseable attributes like configurable attributes and custom options)
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Set the Product Id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set the Product Name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Set the Product Description
     *
     * @param string $desc
     * @return $this
     */
    public function setDescription($desc);

    /**
     * Set the Product Sku
     *
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * Set the Product Price
     *
     * @param float $p
     * @return $this
     */
    public function setPrice($p);

    /**
     * Set the Product Weight
     *
     * @param float $w
     * @return $this
     */
    public function setWeight($w);

    /**
     * Set the Product stock quantity level
     *
     * @param int $qty
     * @return $this
     */
    public function setStockQuantity($qty);

    /**
     * Set the Product manage stock flag
     *
     * @param bool $m
     * @return $this
     */
    public function setManageInventory($m);

    /**
     * Set the Product images
     *
     * @param \Unisho\Sb\ProductVariantImageInterface[] $arr
     * @return $this
     */
    public function setImages($arr);

    /**
     * Set the Product attributes (frontend-chooseable attributes like configurable attributes and custom options)
     *
     * @param array $arr
     * @return $this
     */
    public function setAttributes($arr);
}
