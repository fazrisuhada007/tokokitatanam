<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface ProductVariantImageInterface {
    /**
     * Image position
     *
     * @return int
     */
    public function getDisplayOrder();

    /**
     * Image Id
     *
     * @return int
     */
    public function getPictureId();

    /**
     * Image Url
     *
     * @return string
     */
    public function getPictureUrl();

    /**
     * Image Mime Type
     *
     * @return string
     */
    public function getPictureMimeType();

    /**
     * Image "SEO" filename
     *
     * @return string
     */
    public function getPictureSeoFilename();

    /**
     * Image is new (dunno since when, it always returns fase for now)
     *
     * @return bool
     */
    public function getPictureIsNew();

    /**
     * Sets the image position within it's product
     *
     * @param int $p
     * @return $this
     */
    public function setDisplayOrder($p);

    /**
     * Sets the image id
     *
     * @param int $id
     * @return $this
     */
    public function setPictureId($id);

    /**
     * Sets the image full url. Also gets and sets the mime type if the
     * second parameter is set to true or omitted
     *
     * @param string $url
     * @param bool $get_mime
     * @return $this
     */
    public function setPictureUrl($url, $get_mime = false);

    /**
     * Sets the image mime type (like image/png)
     *
     * @param string $m
     * @return $this
     */
    public function setPictureMimeType($m);

    /**
     * Sets the image SEO filename
     *
     * @param string $f
     * @return $this
     */
    public function setPictureSeoFilename($f);

    /**
     * Sets the image is_new flag
     *
     * @param bool $new
     * @return $this
     */
    public function setPictureIsNew($new);
}
