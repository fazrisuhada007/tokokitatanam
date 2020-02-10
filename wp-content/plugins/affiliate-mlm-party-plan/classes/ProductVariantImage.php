<?php

namespace Unisho\Sb;

use Unisho\Sb\ProductVariantImageInterface;

class ProductVariantImage extends \Unisho\Sb\DataStruct implements ProductVariantImageInterface
{
	protected $_data = array(
		'DisplayOrder' => 0,						// int
		'PictureId' => 0,							// int
		'PictureUrl' => '',							// string
		'PictureMimeType' => '',					// string
		'PictureSeoFilename' => '',					// string
		'PictureIsNew' => false,					// bool
	);

	public function getDisplayOrder() {return $this->_data['DisplayOrder'];}
	public function getPictureId() {return $this->_data['PictureId'];}
	public function getPictureUrl() {return $this->_data['PictureUrl'];}
	public function getPictureMimeType() {return $this->_data['PictureMimeType'];}
	public function getPictureSeoFilename() {return $this->_data['PictureSeoFilename'];}
	public function getPictureIsNew() {return $this->_data['PictureIsNew'];}

	public function setDisplayOrder($p) {$this->_data['DisplayOrder'] = (int)$p; return $this;}
	public function setPictureId($id) {$this->_data['PictureId'] = (int)$id; return $this;}
	public function setPictureUrl($url, $get_mime = false) {
		$this->_data['PictureUrl'] = (string)$url;
		if($get_mime) {
			$ch = curl_init($this->_data['PictureUrl']);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$x = curl_exec($ch);
			$mime_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			$this->_data['PictureMimeType'] = $mime_type;
		}
		return $this;
	}
	public function setPictureMimeType($m) {$this->_data['PictureMimeType'] = $m; return $this;}
	public function setPictureSeoFilename($f) {$this->_data['PictureSeoFilename'] = $f; return $this;}
	public function setPictureIsNew($new) {$this->_data['PictureIsNew'] = (bool)$new; return $this;}
}
