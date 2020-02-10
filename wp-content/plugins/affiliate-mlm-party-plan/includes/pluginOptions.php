<?php

namespace AffiliateUsers\Includes;

class PluginOptions {
	const AFFILIATE_USERS_OPTION = 'affiliate_users';

	/*
	* Get plugin options
	*/
	static public function getPluginOptions() {
		$defaults = array(
			//'choose_affiliate_in_checkout' => 'off',
			//'choose_affiliate_in_checkout_required' => 'off',
			// 'shopping_with_label' => 'off',
			// 'shopping_with_label_text' => 'Shopping With',
			// 'preloader_text' => 'Please Be Patient. Setting Up Your Shopping Experience With',
			// 'preloader_image_url' => '',
			'prefix' => 'r66',
			//'affiliate_title' => 'Affiliate',
		);
		$option = wp_parse_args(get_option(self::AFFILIATE_USERS_OPTION), $defaults);

		return $option;
	}
}
