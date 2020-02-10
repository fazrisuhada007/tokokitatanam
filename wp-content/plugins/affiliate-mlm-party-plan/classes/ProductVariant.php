<?php

namespace Unisho\Sb;
use Unisho\Sb\ProductVariantInterface;
use Unisho\Sb\ProductVariantImageInterface;

class ProductVariant extends \Unisho\Sb\DataStruct implements ProductVariantInterface
{
	protected $_data = array(
		'Id' => 0,                          // int
		'Name' => '',                       // string
		'Description' => '',                // string
		'ProductType' => '',                // string
		'Sku' => '',                        // string
		'Price' => 0.0,                     // decimal
		'Weight' => 0.0,                    // decimal
		'StockQuantity' => 0,               // int
		'ManageInventory' => false,         // bool
		'backorders' => false,         // bool
		'Images' => array(),                // ProductVariantImage[]
		'Attributes' => array(),            // List<ProductAttributeMap> ??
		'Variations' => array(),
		'Deleted' => false,
	);

	protected $_control_types = array(
		'drop_down' => 1,
		'checkbox' => 2,
		'field' => 3,
		'radio' => 4,
	);

	public function __construct($product_id = null) {
		if($product_id) {
			$this->extractValuesFromWcProduct($product_id);
		}

		return $this;
	}

	public function getId()                 {return $this->_data['Id'];}
	public function getName()               {return $this->_data['Name'];}
	public function getDescription()        {return $this->_data['Description'];}
	public function getProductType()        {return $this->_data['ProductType'];}
	public function getSku()                {return $this->_data['Sku'];}
	public function getPrice()              {return $this->_data['Price'];}
	public function getWeight()             {return $this->_data['Weight'];}
	public function getStockQuantity()      {return $this->_data['StockQuantity'];}
	public function getManageInventory()    {return $this->_data['ManageInventory'];}
	public function getImages()             {return $this->_data['Images'];}
	public function getAttributes()         {return $this->_data['Attributes'];}
	public function getVariations()         {return $this->_data['Variations'];}
	public function getDeleted()            {return $this->_data['Deleted'];}

	public function setId($id)              {$this->_data['Id'] = (int)$id;                 return $this;}
	public function setName($name)          {$this->_data['Name'] = (string)$name;          return $this;}
	public function setDescription($desc)   {$this->_data['Description'] = (string)$desc;   return $this;}
	public function setProductType($type)   {$this->_data['ProductType'] = (string)$type;   return $this;}
	public function setSku($sku)            {$this->_data['Sku'] = (string)$sku;            return $this;}
	public function setPrice($p) {
		$this->_data['Price'] = (float)$p;
		return $this;
	}

	public function setWeight($w) {
		$this->_data['Weight'] = (float)$w;
		return $this;
	}

	public function setStockQuantity($qty) {$this->_data['StockQuantity'] = (int)$qty;  return $this;}
	public function setManageInventory($m) {$this->_data['ManageInventory'] = (bool)$m; return $this;}
	public function setBackorder($m) {$this->_data['backorders'] = (bool)$m; return $this;}
	public function setDeleted($d)         {$this->_data['Deleted'] = (bool)$d; return $this;}


	public function setImages($images) {
		$this->_data['Images'] = array();
		foreach($images as $img_arr) {
			$image = new ProductVariantImage();
			$image->setDisplayOrder($img_arr['DisplayOrder']);
			$image->setPictureId($img_arr['PictureId']);
			$image->setPictureUrl($img_arr['PictureUrl']);
			$image->setPictureMimeType($img_arr['PictureMimeType']);
			if(array_key_exists('PictureSeoFilename', $img_arr)) {
				$image->setPictureSeoFilename($img_arr['PictureSeoFilename']);
			}
			if(array_key_exists('PictureIsNew', $img_arr)) {
				$image->setPictureIsNew($img_arr['PictureIsNew']);
			}
			$this->_data['Images'][] = $image;
		}
		return $this;
	}

	public function setAttributes($attributes) {
		foreach($attributes as $attr) {
			$this->_data['Attributes'][] = $attr;
		}
		return $this;
	}

	public function setVariations($vars) {
		if(is_array($vars) == false) {return $this;}
		$this->_data['Variations'] = array();
		foreach($vars as $var) {
			$this->_data['Variations'][] = $var;
		}
		return $this;
	}

	public function extractAttributesFromProductId($product_id) {
		$product_wp = get_post($product_id);
		if($product_wp && $product_wp->ID != $product_id) {
			return array();
		}
		if($product_wp->post_type != 'product') {
			return array();
		}
		setup_postdata($product_wp);
		$product_wc = new \WC_Product($product_wp);
		$this->extractAttributes($product_wc);
		return $this->getAttributes();
	}

	public function extractValuesFromWcProduct($product_id) {
		$product_wp = get_post($product_id);
		if($product_wp && $product_wp->ID != $product_id) {
			return $this;
		}
		if($product_wp->post_type != 'product') {
			return array();
		}
		if($product_wp->post_status == 'draft' || $product_wp->post_status == 'auto-draft') {
			return array();
		}
		setup_postdata($product_wp);
		$this->setId($product_wp->ID);
		$this->setName(get_the_title($product_wp));
		$this->setDescription(get_the_content());
		$this->setSku($product_wp->_sku);
		$this->setPrice($product_wp->_price);
		$this->setWeight($product_wp->_weight);
		if($product_wp->post_status == 'trash') {
			$this->setDeleted(true);
		} else {
			$this->setDeleted(false);
		}
		if($product_wp->_manage_stock == 'yes') {
			$this->setManageInventory(true);
		} else {
			$this->setManageInventory(false);
		}
		if($product_wp->backorders == 'yes') {
			$this->setBackorder(true);
		} else {
			$this->setBackorder(false);
		}
		$this->setStockQuantity($product_wp->_stock);

		$wp_uploads_dir = '';
		if(defined('UPLOADS')) {
			$wp_uploads_dir = rtrim(UPLOADS, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
		} else {
			$wp_uploads_dir = WP_CONTENT_DIR.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
		}
		$images = array();
		$image_order = 0;
		if(has_post_thumbnail($product_id)) {
			$image_id = get_post_thumbnail_id($product_id);
			$image_meta = wp_get_attachment_metadata($image_id);
			
			$images[] = array(
				'DisplayOrder' => $image_order++,
				'PictureId' => $image_id,
				/*'PictureMimeType' => image_type_to_mime_type(exif_imagetype($wp_uploads_dir.$image_meta['file'])),*/
				'PictureMimeType' => isset($image_meta['sizes']['thumbnail']['mime-type']) ? $image_meta['sizes']['thumbnail']['mime-type'] : image_type_to_mime_type(exif_imagetype($wp_uploads_dir.$image_meta['file'])),
				'PictureUrl' => wp_get_attachment_url($image_id)
			);
		}

		$gallery_image_ids = $product_wp->_product_image_gallery;
		if($gallery_image_ids != '') {
			$gallery_image_ids = explode(',', $gallery_image_ids);
			foreach($gallery_image_ids as $image_id) {
				$image_meta = wp_get_attachment_metadata($image_id);
				$images[] = array(
					'DisplayOrder' => $image_order++,
					'PictureId' => $image_id,
					'PictureMimeType' => isset($image_meta['sizes']['thumbnail']['mime-type']) ? $image_meta['sizes']['thumbnail']['mime-type'] : image_type_to_mime_type(exif_imagetype($wp_uploads_dir.$image_meta['file'])),
					'PictureUrl' => wp_get_attachment_url($image_id)
				);
			}
		}
		$this->setImages($images);
		// $product_wc = new \WC_Product($product_wp);
		$product_wc = get_product($product_wp);
		$this->setProductType($product_wc->product_type);

		$this->extractAttributes($product_wc);
		$this->extractVariations($product_id);
		return $this;
	}

	public function extractVariations($product_id) {
		$attr_vars = array();
		$attributes = $this->getAttributes();
		foreach($attributes as $attr) {
			$attr_vars[$attr['Id']] = array();
			foreach($attr['AttributeValues'] as $attr_val) {
				$attr_vars[$attr['Id']][] = $attr_val['Id'];
			}
		}
		if(empty($attr_vars)) {
			$this->setVariations(array());
			return $this;
		}
		$args = array(
			'post_parent' => $product_id,
			'post_type' => 'product_variation',
			'numberposts' => -1,
			'post_status' => 'any'
		);
		$variations_raw = get_children( $args, $output );
		$variations = array();

		foreach($variations_raw as $var) {
			$variation = new \WC_Product_Variation($var);
			$variations[] = $variation;
		}

		
		$varrs = array();
		foreach($variations as $variation) {
			$var_post = get_post($variation->variation_id);
			$variation_values = array();
			foreach($attr_vars as $attr_code => $values) {
				$x = 'attribute_'.$attr_code;
				if($var_post->$x) {
					foreach($values as $value) {
						if(trim($var_post->$x) == trim($value)) {
							$variation_values[] = array(
								'Id' => $attr_code,
								'choice' => trim($value)
							);
							break;
						}
					}
				} else {
					$variation_values[] = array(
						'Id' => $attr_code,
						'choice' => ''
					);
				}
			}
			$isBackorder = false;
			if($variation->backorders == "yes"){
				$isBackorder = true;
			}

			$manageStock = false;
			if($variation->manage_stock == "yes"){
				$manageStock = true;
			}

			$stock_quantity = $variation->stock_quantity;
			if(empty($stock_quantity) || $stock_quantity == null){
				$stock_quantity = 0;
			}

			$v = array(
				'VariationId' => $variation->variation_id,
				'AttributeChoice' => $variation_values,
				'Price' => $var_post->_price,
				'Sku' => $var_post->_sku,
				'StockQuantity' => $stock_quantity,
				'ManageInventory' => $manageStock,
				'backorders' => $isBackorder,
			);
			$varrs[] = $v;
		}
		$this->setVariations($varrs);
	}



	public function extractAttributes($wc_product) {
		$product_id = $wc_product->post->ID;
		$attr = $wc_product->get_attributes();
		$attributes = array();
		$d = 0;
		foreach($attr as $k => $v) {

			if(WC()->version < "3.0.0"){
				if($v['is_variation'] == 0) {continue;}
			}else{
				if($v->get_variation() == 0) {continue;}
			}

			$attribute = array(
				'Id' => $k,
				// 'AttributeType' => 'A',
				'ProductId' => $product_id,
				'ControlType' => 1,
				'IsRequired' => true,
				'DisplayOrder' => $d++, // ++ intended
				'AttributeValues' => array(),
			);

			$v_is_taxonomy = false;
			if(WC()->version < "3.0.0"){
				$v_is_taxonomy = $v['is_taxonomy'];
			}else{
				$v_is_taxonomy = $v->is_taxonomy();
			}

			if($v_is_taxonomy) {
				if(WC()->version < "3.0.0"){
					$attribute['name'] = wc_attribute_label($v['name'], $product_id);
				}else{
					$attribute['name'] = wc_attribute_label($v->get_name());
				}

				if(WC()->version < "3.0.0"){
					$terms = wp_get_post_terms($product_id, $k);
				}else{
					$terms = $v->get_terms();
				}
				
				if(count($terms)) {
					foreach($terms as $term) {
						$attribute['AttributeValues'][] = array(
							'Id' => $term->slug,
							'Name' => trim($term->name),
						);
					}
				}
			} else {
				if(WC()->version < "3.0.0"){
					$attribute['name'] = $v['name'];
					$vars = explode('|', $v['value']);
				}else{
					$attribute['name'] = $v->get_name();
					$vars = explode('|', $v->offsetGet('value'));
				}
				
				foreach($vars as $var) {
					$attribute['AttributeValues'][] = array(
						'Id' => trim($var),
						'Name' => trim($var),
					);
				}
			}

			$attributes[] = $attribute;
		}

		$this->setAttributes($attributes);

		return $this;
	}

}
