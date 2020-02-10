<?php
/*
Plugin Name: AFFILIATE MLM / PARTY PLAN
Plugin URI: https://mlm-socialbug.com/
Description: A plugin to handle several specific methods for integration with SocialBug. Requires a valid Socialbug PAID subscription. 
Version: 2.6.6
Author: SocialBug
Author URI: https://mlm-socialbug.com
Requires at least: 4.4.2
Tested up to: 5.2.2
*/

define('AFFILIATE_USERS_DIR', plugin_dir_path(__FILE__));
define('AFFILIATE_USERS_URL', plugin_dir_url(__FILE__));
define('AFFILIATE_USERS_PAGE', __FILE__);

require_once(AFFILIATE_USERS_DIR . 'includes/sb.php');

require_once(AFFILIATE_USERS_DIR . 'includes/pluginOptions.php');
require_once(AFFILIATE_USERS_DIR . 'includes/usersRoutes.php');
require_once(AFFILIATE_USERS_DIR . 'includes/usersList.php');
require_once(AFFILIATE_USERS_DIR . 'includes/optionsBuilder.php');
require_once(AFFILIATE_USERS_DIR . 'includes/db.php');

require_once(AFFILIATE_USERS_DIR . 'includes/activation.php');

$options = \AffiliateUsers\Includes\PluginOptions::getPluginOptions();
$routes = new \AffiliateUsers\Includes\UsersRoutes();
add_action('init', array($routes, 'addRoutes'));
add_action('switch_theme', array($routes, 'setRoutes'));
add_action('wp', array($routes, 'checkUser'));
// if (isset($options['shopping_with_label']) && ($options['shopping_with_label'] == 'on')) {
// 	add_action('wp_head', array($routes, 'buffer'));
// 	add_action('wp_footer', array($routes, 'insertShoppingWith'));
// }
add_filter('query_vars', array($routes, 'addQueryVars'));

$clients_list = new \AffiliateUsers\Includes\UsersList();
add_action('admin_init', array($clients_list, 'process_bulk_action'));

$options_builder = new \AffiliateUsers\Includes\OptionsBuilder(__('Affiliate Users'));

$options_builder->addTab('main', __('Main Settings'));
$options_builder->addTab('users', __('Registered Users'));

$options_builder->addSection('main_section', __('Main Settings'), 'main');

// $options_builder->addOption('choose_affiliate_in_checkout', __('Choose Affiliate in Checkout'), 'checkbox', 'main_section');
// $options_builder->addOption('choose_affiliate_in_checkout_required', __('Choose Affiliate in Checkout Required'), 'checkbox', 'main_section');

// $options_builder->addOption('shopping_with_label', __('Shopping With Label'), 'checkbox', 'main_section');
// $options_builder->addOption('shopping_with_label_text', __('Shopping With Label Text'), 'text', 'main_section');

// $options_builder->addOption('preloader_text', __('Preloader Text'), 'text', 'main_section');
// $options_builder->addOption('preloader_image_url', __('Preloader Image URL'), 'text', 'main_section');

$options_builder->addOption('prefix', __('Affiliate URL Prefix'), 'text', 'main_section');
//$options_builder->addOption('affiliate_title', __('Affiliate Title'), 'text', 'main_section');

//added for use tracker at 31-01-2017

$options_builder->addOption('use_tracker', __('Use Tracker'), 'checkbox', 'main_section');
$options_builder->addOption('use_tracker_url', __('Tracker Url'), 'text', 'main_section');

add_action('admin_menu', array($options_builder, 'optionsPage'));
add_action('admin_init', array($options_builder, 'settings'));
