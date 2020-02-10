<?php

require_once( AFFILIATE_USERS_DIR . 'includes/socialbug-api.php' );
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Suppressing error
 *
 * @var Ambiguous $err_reporting_check
 */
$err_reporting_check = get_option( 'sb_error_suppress' );
if ( empty( $err_reporting_check ) ) {
	error_reporting( 0 );
} else {
	error_reporting( 1 );
}

/**
 * Sb_autoload
 *
 * $class_name dddd
 */
function sb_autoload( $class_name ) {
	$parts = explode( '\\', $class_name );
	if ( count( $parts ) > 2 && 'Unisho' == $parts[0] && 'Sb' == $parts[1] ) {
		array_shift( $parts );
		array_shift( $parts );
		$class_path = '';
		if ( substr( end( $parts ), -9 ) == 'Interface' ) {
			$class_path .= 'interfaces' . DIRECTORY_SEPARATOR;
		}

		$class_path .= implode( DIRECTORY_SEPARATOR, $parts );
		$filename = AFFILIATE_USERS_DIR . 'classes/' . $class_path . '.php';
		if ( file_exists( $filename ) ) {
			include_once( $filename );
		}
	}
}

spl_autoload_register( 'sb_autoload' );
global $sb_version;
$sb_version = '1.5';

function sb_create_table() {
	global $wpdb;
	global $sb_version;
	$integrations_table_name = $wpdb->prefix . 'sb_integrations';
	$charset_collate = $wpdb->get_charset_collate();
	$sql_integrations = "CREATE TABLE $integrations_table_name (
	integration_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	name varchar(100) NOT NULL,
	created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	guid varchar(36) DEFAULT '' NOT NULL,
	status tinyint NOT NULL,
	UNIQUE KEY guid (guid(36)),
	PRIMARY KEY  (integration_id)
	) ENGINE=InnoDB $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql_integrations );
	add_option( 'sb_version', $sb_version );
}

function sb_admin_menu() {
	$page_hook = add_menu_page( 'Integrations', 'Integrations', 'edit_pages', 'sb_integrations_list', 'sb_admin_integrations_list', 'dashicons-admin-home', '7.62' );
	add_action( 'load-' . $page_hook, 'sb_admin_integrations_list_screen_options' );
}

function sb_admin_integrations_list_pagination( $nr_items, $items_per_page = 30, $current_page = 1, $filter = null, $order = null ) {
	$extra_params = '';
	if ( $filter ) {
		$extra_params .= '&orderby=' . $filter;
	}

	if ( $order ) {
		$extra_params .= '&order=' . $order;
	}

	$nr_pages = ceil( $nr_items / $items_per_page );
	if ( $items_per_page >= $nr_items ) {
		$cclass = 'tablenav-pages one-page';
	} else {
		$cclass = 'tablenav-pages';
	}

	$html = '';
	$html .= '<span class="displaying-num">' . $nr_items . ' items</span>';
	if( 1 == $current_page ) {
		$first = '<a class="first-page disabled" title="Go to the first page" href="' . admin_url('admin.php?page=sb_integrations_list&paged=1' . $extra_params).'">�</a>';
		$prev = '<a class="prev-page disabled" title="Go to the previous page" href="' . admin_url('admin.php?page=sb_integrations_list&paged=1' . $extra_params).'">�</a>';
	} else {
		$nr_prev = $current_page - 1;
		$first = '<a class="first-page" title="Go to the first page" href="' . admin_url('admin.php?page=sb_integrations_list&paged=1' . $extra_params).'">�</a>';
		$prev = '<a class="prev-page" title="Go to the previous page" href="' . admin_url('admin.php?page=sb_integrations_list&paged=' . $nr_prev . $extra_params).'">�</a>';
	}

    if($current_page == $nr_pages) {
        $next = '<a class="next-page disabled" title="Go to the next page" href="'.admin_url('admin.php?page=sb_integrations_list&paged='.$nr_pages.$extra_params).'">�</a>';
        $last = '<a class="last-page disabled" title="Go to the last page" href="'.admin_url('admin.php?page=sb_integrations_list&paged='.$nr_pages.$extra_params).'">�</a>';
    } else {
        $nr_next = $current_page + 1;
        $next = '<a class="next-page disabled" title="Go to the next page" href="'.admin_url('admin.php?page=sb_integrations_list&paged='.$nr_next.$extra_params).'">�</a>';
        $last = '<a class="last-page disabled" title="Go to the last page" href="'.admin_url('admin.php?page=sb_integrations_list&paged='.$nr_pages.$extra_params).'">�</a>';
    }
    $html = '
    <div class="tablenav top">
        <div class="'.$cclass.'">
            <span class="displaying-num">'.$nr_items.' items</span>
            <span class="pagination-links">
                '.$first.'
                '.$prev.'
                <span class="paging-input">
                    <label for="current-page-selector" class="screen-reader-text">Select Page</label>
                    <input class="current-page" id="current-page-selector" title="Current page" name="paged" value="'.$current_page.'" size="1" type="text">
                    of <span class="total-pages">'.$nr_pages.'</span>
                </span>
                '.$next.'
                '.$last.'
            </span>
        </div>
        <br class="clear" />
    </div>';
    return $html;
}

function sb_get_nr_integrations() {
	global $wpdb;
	$nr = $wpdb->get_var( 'SELECT COUNT(*) FROM `' . $wpdb->prefix . 'sb_integrations`');
	return $nr;
}

function sb_get_all_integrations($start = 0, $limit = 30, $filter = null, $order = 'ASC') {
    global $wpdb;
    $sql = 'SELECT * FROM `'.$wpdb->prefix.'sb_integrations`';
    if ($filter) {
       $sql .= ' ORDER BY '.$filter.' '.$order;
    }
    $sql .= ' LIMIT '.$start.','.$limit;
    $integrations_raw = $wpdb->get_results($sql, ARRAY_A);
    $integrations = array();
    foreach ($integrations_raw as $intg) {
        $integrations[$intg['integration_id']] = $intg;
    }
    return $integrations;
}

function sb_admin_integrations_list() {
    if( array_key_exists('page_type', $_GET ) && 'edit' == $_GET[ 'page_type'] ) {
        return sb_admin_integration_edit();
    }
    if(array_key_exists('page_type', $_GET) && 'settings' == $_GET['page_type'] ) {
        $this_page = 'settings';
    } else {
        $this_page = 'list';
    }

    if($this_page == 'settings' && array_key_exists('sb_save_settings', $_POST)) {
        check_admin_referer( 'sb_save_settings' );
        $options = array('sb_error_suppress', 'sb_api_key', 'sb_api_url', 'sb_redirect_url', 'sb_page_title', 'sb_api_setup', 'sb_footer_script');
      
        foreach($options as $option) {
            if( $option === 'sb_footer_script' ){
              update_option($option, $_POST[$option] );
            }else{
              update_option($option, sanitize_text_field($_POST[$option]) );
            }
            
        }
        wp_redirect(admin_url('admin.php?page=sb_integrations_list&page_type=settings&saved_ok=1'));
        return;
    }

    $saved_ok = false;
    if($this_page == 'settings') {
        $sb_error_suppress = get_option('sb_error_suppress');
        $sb_api_key = get_option('sb_api_key');
        $sb_api_url = get_option('sb_api_url');
        $sb_redirect_url = get_option('sb_redirect_url');
        $sb_page_title = get_option('sb_page_title');
        $sb_api_setup = get_option('sb_api_setup');
        $sb_footer_script = get_option('sb_footer_script');
        if(array_key_exists('saved_ok', $_GET) && $_GET['saved_ok'] == '1') {
            $saved_ok = true;
        }    
    } else {
        $user = get_current_user_id();
        $screen = get_current_screen();
        $option = $screen->get_option('per_page', 'option');
        $per_page = get_user_meta($user, $option, true);
        if(empty($per_page) || $per_page < 1) {
            $per_page = $screen->get_option('per_page', 'default');
        }
        $nr_integrations = sb_get_nr_integrations();
        $nr_pages = ceil($nr_integrations / $per_page);
        $current_page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
        if($current_page < 1) {$current_page = 1;}
        $nr_pages = ((int) $nr_pages < 1) ? 1 : $nr_pages;
        if($current_page > $nr_pages) {$current_page = $nr_pages;}
        if(array_key_exists('orderby', $_GET)) {
            $filter = sanitize_text_field( $_GET['orderby'] );
        } else {
            $filter = null;
        }

        if(array_key_exists('order', $_GET)) {
            $order = strtoupper( sanitize_text_field($_GET['order'] ) );
            if($order == 'ASC') {
                $new_order = 'DESC';
            } else {
                $new_order = 'ASC';
            }
        } else {
            $order = null;
            $new_order = 'ASC';
        }

        $order_class_id = 'asc';
        $order_class_name = 'asc';
        $order_class_guid = 'asc';
        $order_class_created_at = 'asc';
        $order_class_status = 'asc';

        if($filter) {
            ${'order_class_'.$filter} = strtolower($order);
        }

        $integrations = sb_get_all_integrations(($current_page - 1) * $per_page, $per_page, $filter, $order);
        $pagination = sb_admin_integrations_list_pagination($nr_integrations, $per_page, $current_page, $filter, $order);
    }

    ?>

    <div class="wrap">
        <h2>Integrations <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&page_type=edit&integration_id=0'); ?>" class="page-title-action">Add New</a></h2>
        <?php if($saved_ok) { ?>
            <div id="message" class="updated inline"><p><strong>Your settings have been saved.</strong></p></div>
            <?php } ?>
            <ul class="subsubsub">
               <li class="list"><a href="<?php echo admin_url('admin.php?page=sb_integrations_list&page_type=list'); ?>"<?php if($this_page == 'list') { echo ' class="current"';} ?>>List</a> |</li>

                <li class="settings"><a href="<?php echo admin_url('admin.php?page=sb_integrations_list&page_type=settings'); ?>"<?php if($this_page == 'settings') { echo ' class="current"';} ?>>Settings</a></li>
            </ul>
            <?php if($this_page == 'settings') { ?>
                <style>.sb-settings-form td input {width: 100%;}</style>
                <style>
                    .switch {
                      position: relative;
                      display: inline-block;
                      width: 60px;
                      height: 34px;
                    }
                  
                   .switch input {display:none;}
                  
                    .slider {
                      position: absolute;
                      cursor: pointer;
                      top: 0;
                      left: 0;
                      right: 0;
                      bottom: 0;
                      background-color: #ccc;
                      -webkit-transition: .4s;
                      transition: .4s;
                    }
                 
                    .slider:before {
                      position: absolute;
                      content: "";
                      height: 26px;
                      width: 26px;
                      left: 4px;
                      bottom: 4px;
                      background-color: white;
                      -webkit-transition: .4s;
                      transition: .4s;
                    }
                   
                    input:checked + .slider {
                      background-color: #2196F3;
                    }
                   
                    input:focus + .slider {
                      box-shadow: 0 0 1px #2196F3;
                    }
                    
                    input:checked + .slider:before {
                      -webkit-transform: translateX(26px);
                      -ms-transform: translateX(26px);
                      transform: translateX(26px);
                    }
                 
                    /* Rounded sliders */
                    .slider.round {
                      border-radius: 34px;
                    }
                    
                    .slider.round:before {
                      border-radius: 50%;
                    }
                </style>
                <form method="post" class="sb-settings-form">
                    <input type="hidden" name="sb_save_settings" value="1" />
                    <table class="form-table">
                        <tbody>
                        <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <label for="sb_api_key">Show Warnings and Errors</label>
                                </th>
                                <td class="forminp forminp-text">
                                   <label class="switch">
                                     <input type="checkbox" name="sb_error_suppress" <?php if($sb_error_suppress == 'on'){ echo 'checked';}else{ echo ""; }?>>
                                      <div class="slider round"></div>
                                   </label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <label for="sb_api_key">API Key</label>
                                </th>
                                <td class="forminp forminp-text">
                                    <input name="sb_api_key" id="sb_api_key" type="text" value="<?php echo $sb_api_key; ?>" class="" placeholder="" />
                               </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                   <label for="sb_api_url">Prefix URL</label>
                                </th>
                                <td class="forminp forminp-text">
                                    <input name="sb_api_url" id="sb_api_url" type="text" value="<?php echo $sb_api_url; ?>" class="" placeholder="" />
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <label for="sb_redirect_url">Redirect URL</label>
                                </th>
                                <td class="forminp forminp-text">
                                    <input name="sb_redirect_url" id="sb_redirect_url" type="text" value="<?php echo $sb_redirect_url; ?>" class="" placeholder="" />
                                </td>
                           </tr>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <label for="sb_page_title">Page Title</label>
                                </th>
                                <td class="forminp forminp-text">
                                    <input name="sb_page_title" id="sb_page_title" type="text" value="<?php echo $sb_page_title; ?>" class="" placeholder="" />
                                </td>
                            </tr>
                            <tr valign="top">

                               <th scope="row" class="titledesc">
                                   <label for="sb_api_setup">API Setup</label>
                                </th>
                                <td class="forminp forminp-text">
                                    <input name="sb_api_setup" id="sb_api_setup" type="text" value="<?php echo $sb_api_setup; ?>" class="" placeholder="" />
                                </td>
                            </tr>
                            <tr valign="top">
                               <th scope="row" class="titledesc">
                                   <label for="sb_footer_script">Footer HTML</label>
                                </th>
                                <td class="forminp forminp-text">
                                    <input name="sb_footer_script" id="sb_footer_script" type="text" value="<?php echo esc_attr(stripslashes($sb_footer_script)); ?>" class="" placeholder="" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p class="submit">
                        <input name="save" class="button-primary woocommerce-save-button" type="submit" value="Save changes">
                        <?php wp_nonce_field( 'sb_save_settings' ); ?>
                    </p>
                </form>
                <?php } else { ?>
                    <form id="posts-filter" method="get">
                        <p class="search-box" style="display: none;">
                            <label class="screen-reader-text" for="post-search-input">Search Integrations:</label>
                            <input id="post-search-input" name="s" value="" type="search" />
                            <input id="search-submit" class="button" value="Search Companies" type="submit" />
                        </p>
                        <?php echo $pagination; ?>
                        <table class="wp-list-table widefat fixed striped pages">
                            <thead>
                                <tr>
                                    <th scope="col" id="integration_id" class="manage-column column-integrationid sortable <?php echo $order_class_id; ?>" width="60">

                                        <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=integration_id&order='.$new_order); ?>"><span>ID</span><span class="sorting-indicator"></span></a>
                                    </th>
                                    <th scope="col" id="name" class="manage-column column-name sortable <?php echo $order_class_name; ?>">
                                        <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=name&order='.$new_order); ?>"><span>Name</span><span class="sorting-indicator"></span></a>
                                    </th>
                                    <th scope="col" id="guid" class="manage-column column-guid sortable <?php echo $order_class_guid; ?>">

                                        <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=guid&order='.$new_order); ?>"><span>API KEY</span><span class="sorting-indicator"></span></a>

                                    </th>

                                    <th scope="col" id="created_at" class="manage-column column-created_at sortable <?php echo $order_class_created_at; ?>">

                                        <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=created_at&order='.$new_order); ?>"><span>Created at</span><span class="sorting-indicator"></span></a>

                                    </th>

                                    <th scope="col" id="status" class="manage-column column-status sortable <?php echo $order_class_status; ?>">

                                        <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=status&order='.$new_order); ?>"><span>Status</span><span class="sorting-indicator"></span></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="the-list">
                            <?php if ( isset( $integrations ) ){ ?>
                                <?php foreach($integrations as $integration) { ?>
                                    <?php $iid = $integration['integration_id']; ?>
                                    <tr id="post-<?php echo $iid ?>" class="status-publish hentry">
                                        <td class="column-integrationid">
                                            <?php echo $iid; ?>
                                        </td>
                                        <td class="post-name column-name">
                                            <strong><a class="row-title" href="<?php echo admin_url('admin.php?page=sb_integrations_list&page_type=edit&integration_id='.$iid); ?>" title="Edit �<?php echo $integration['name']; ?>�"><?php echo $integration['name']; ?></a></strong>
                                        </td>
                                        <td class="column-guid">
                                            <?php echo $integration['guid']; ?>
                                        </td>
                                        <td class="column-created_at">
                                            <?php echo $integration['created_at']; ?>
                                        </td>
                                        <td class="column-status">
                                            <?php if($integration['status'] == 0) {
                                                echo 'Disabled';
                                            } else {
                                                echo 'Enabled';
                                            } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                               <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th scope="col" id="integration_id" class="manage-column column-integrationid sortable <?php echo $order_class_id; ?>" width="60">

                                            <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=integration_id&order='.$new_order); ?>"><span>ID</span><span class="sorting-indicator"></span></a>

                                        </th>

                                        <th scope="col" id="name" class="manage-column column-name sortable <?php echo $order_class_name; ?>">

                                            <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=name&order='.$new_order); ?>"><span>Name</span><span class="sorting-indicator"></span></a>

                                        </th>

                                        <th scope="col" id="guid" class="manage-column column-guid sortable <?php echo $order_class_guid; ?>">

                                            <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=guid&order='.$new_order); ?>"><span>API KEY</span><span class="sorting-indicator"></span></a>

                                        </th>

                                        <th scope="col" id="created_at" class="manage-column column-created_at sortable <?php echo $order_class_created_at; ?>">

                                            <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=created_at&order='.$new_order); ?>"><span>Created at</span><span class="sorting-indicator"></span></a>

                                        </th>

                                        <th scope="col" id="status" class="manage-column column-status sortable <?php echo $order_class_status; ?>">

                                            <a href="<?php echo admin_url('admin.php?page=sb_integrations_list&orderby=status&order='.$new_order); ?>"><span>Status</span><span class="sorting-indicator"></span></a>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                            <?php echo $pagination; ?>
                        </form>
                        <?php } ?>
                        <div id="ajax-response"></div>
                        <br class="clear" />
                    </div>
                    <?php
}

function sb_get_integration($integration_id) {
	$integration_id = absint($integration_id);
	if($integration_id == 0) {
		return array(
			'integration_id' => '',
			'guid' => '',
			'name' => '',
			'status' => 1
			);
	}

	global $wpdb;
	$integration = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix.'sb_integrations WHERE integration_id = %d', $integration_id ), ARRAY_A);
	return $integration;
}

function sb_create_guid() {
	$uid = uniqid('', true);
	$data = AFFILIATE_USERS_PAGE;
	$data .= $_SERVER['REQUEST_TIME'];
	$data .= $_SERVER['HTTP_USER_AGENT'];
	$data .= $_SERVER['LOCAL_ADDR'];
	$data .= $_SERVER['LOCAL_PORT'];
	$data .= $_SERVER['REMOTE_ADDR'];
	$data .= $_SERVER['REMOTE_PORT'];
	$hash = strtoupper(hash('ripemd128', $uid . md5($data)));
	$guid =
	substr($hash,  0,  8).'-'.
	substr($hash,  8,  4).'-'.
	substr($hash, 12,  4).'-'.
	substr($hash, 16,  4).'-'.
	substr($hash, 20, 12);
	return $guid;
}

function sb_admin_integration_save() {
	$integration_id = absint( $_POST['integration_id'] );
	$user = get_current_user_id();
	global $wpdb;
	$keys = array('name', 'guid');
	
	foreach ( $keys as $k ) {
		${$k} = trim( str_replace( array( '\\\'', '\\\\' ), array( "'", '\\' ), sanitize_text_field( $_POST[$k] ) ) );
	}
	$status = absint( $_POST['status'] );
	
	if ( $status > 1 ) {
		$status = 0;
	}
	
	if( $name == '' ) {
		update_user_meta( $user, 'sb_integration_edit_message', 'Error saving the integration. Invalid Name!' );
		update_user_meta( $user, 'sb_integration_edit_message_class', 'error' );
		wp_redirect( admin_url( 'admin.php?page=sb_integrations_list&page_type=edit&integration_id=' . $integration_id ) );
		die();
	}

	if ( $guid == '' ) {
		$guid = sb_create_guid();
	} else {
		$guid = strtoupper( trim($guid) );
		$error = false;
		if ( strlen($guid) != 36 ) {
			$error = true;
		} else {
			$data1  = substr( $guid,  0,  8 );
			$dash1  = substr( $guid,  8,  1 );
			$data2  = substr( $guid,  9,  4 );
			$dash2  = substr( $guid, 13,  1 );
			$data3  = substr( $guid, 14,  4 );
			$dash3  = substr( $guid, 18,  1 );
			$data4a = substr( $guid, 19,  4 );
			$dash4  = substr( $guid, 23,  1 );
			$data4b = substr( $guid, 24, 12 );
			
			if ( $dash1.$dash2.$dash3.$dash4 != '----' ) {
				$error = true;
			} else {
				$data_all = $data1 . $data2 . $data3 . $data4a . $data4b;
				$chars = str_split( $data_all );
				$allowed_chars = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'A', 'B', 'C', 'D', 'E', 'F' );
				
				foreach ( $chars as $c ) {
					if ( in_array( $c, $allowed_chars ) == false ) {
						$error = true;
						break;
					}
				}
			}
		}
		
		if( $error ) {
			update_user_meta( $user, 'sb_integration_edit_message', 'Error saving the integration. Invalid API KEY!' );
			update_user_meta( $user, 'sb_integration_edit_message_class', 'error' );
			wp_redirect( admin_url( 'admin.php?page=sb_integrations_list&page_type=edit&integration_id=' . $integration_id ) );
			die();
		}
	}
	if( $integration_id ) {
		$result = $wpdb->update(
			$wpdb->prefix . 'sb_integrations',              // table
			array(                                        // columns to fill
				'name' => $name,
				'guid' => $guid,
				'status' => $status
			),
			array('integration_id' => $integration_id),   // where
			array(                                        // columns type
				'%s', '%s', '%d'
			),
			'%d'                                          // where type
		);
	} else {
		$result = $wpdb->insert(
			$wpdb->prefix . 'sb_integrations',
			array(
				'name' => $name,
				'guid' => $guid,
				'status' => $status,
				'created_at' => current_time( 'mysql' )
			),
			array(
				'%s', '%s', '%d', '%s'
			)
		);
		$integration_id = $wpdb->insert_id;
	}
	if ( $result !== false && $integration_id ) {
		update_user_meta( $user, 'sb_integration_edit_message', 'Integration saved successfully.' );
		update_user_meta( $user, 'sb_integration_edit_message_class', 'notice' );
	} else {
		update_user_meta( $user, 'sb_integration_edit_message', 'Error saving the integration. Check the values and try again.' );
		update_user_meta( $user, 'sb_integration_edit_message_class', 'error' );
	}
	wp_redirect( admin_url( 'admin.php?page=sb_integrations_list&page_type=edit&integration_id=' . $integration_id ) );
	die();
}

function sb_admin_integration_delete() {
	$integration_id = absint($_POST['integration_id']);
	global $wpdb;
	$wpdb->delete($wpdb->prefix.'sb_integrations', array('integration_id' => $integration_id), '%d');
	wp_redirect(admin_url('admin.php?page=sb_integrations_list'));
	die();
}

function sb_admin_integration_edit() {
	// wp_enqueue_media();
	wp_enqueue_script( 'custom-header' );
	$integration_id = sanitize_text_field( $_GET['integration_id'] );
	$integration = sb_get_integration($integration_id);
	global $wpdb;
	$user = get_current_user_id();
	$edit_message = get_user_meta($user, 'sb_integration_edit_message', true);
	$edit_message_class = get_user_meta($user, 'sb_integration_edit_message_class', true);
	delete_user_meta($user, 'sb_integration_edit_message');
	delete_user_meta($user, 'sb_integration_edit_message_class');
	?>
	<div class="wrap js integration_edit_page">
		<style>
			.form_element {margin: 20px 0;}
			.form_element:first-child {margin-top: 0;}
			.form_element .element_label {font-size: 20px; font-weight: bold; line-height: 2;}
			.postbox .form_element .element_label {font-size: 16px;}
			#poststuff h3 {font-size: 20px;}
			.form_element input[type="text"], .form_element textarea {width: 100%; box-sizing: border-box;}
			.integration_edit_page .postbox .handlediv {height: 45px;}
			.integration_edit_page.js .meta-box-sortables .postbox .handlediv::before {padding: 12px 10px;}
		</style>
		<?php if($integration_id) { ?>
			<h2>Edit Integration</h2>
			<?php } else { ?>
				<h2>Add Integration</h2>
				<?php } ?>
				<?php if(!empty($edit_message)) { ?>
					<div id="message" class="updated <?php echo $edit_message_class; ?> is-dismissible below-h2">
						<p><?php echo $edit_message; ?></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">Dismiss this notice.</span>
						</button>
					</div>
					<?php } ?>
					<form name="post" action="" method="post" id="post" enctype="multipart/form-data">
						<input type="hidden" name="integration_id" value="<?php echo $integration_id; ?>" />
						<input type="hidden" name="action" value="update" id="form_action" />
						<div id="poststuff">
						   <div id="post-body" class="metabox-holder columns-2">
								<div id="post-body-content">
									<div id="titlediv" class="form_element">
										<div id="titlewrap">
											<label for="name" class="element_label">Name:</label><br />
											<input type="text" name="name" value="<?php echo $integration['name']; ?>" id="name" autocomplete="off" />
										</div>
									</div>
									<div class="form_element">
										<label for="guid" class="element_label">API KEY:</label><br />
										<input type="text" name="guid" value="<?php echo $integration['guid']; ?>" id="guid" placeholder="XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX" /><br >
										<div class="description">Leave the API KEY blank to autogenerate one.</div>
									</div>
								</div>
								<div id="postbox-container-1" class="postbox-container meta-box-sortables">
									<div id="submitdiv" class="postbox">
										<div class="handlediv" title="Click to toggle"><br /></div>
										<h3 class="hndle"><span>Status</span></h3>
										<div class="inside">
											<div class="submitbox" id="submitpost">
												<div id="minor-publishing">
													<div id="misc-publishing-actions">
														<div class="form_element" style="padding: 0 12px;">
															<label class="element_label">Status:</label><br />
															<label><input type="radio" name="status" value="1" id="status_1" />Enabled</label><br />
															<label><input type="radio" name="status" value="0" id="status_0" />Disabled</label>
														</div>
													</div>
													<div class="clear"></div>
												</div>
												<div id="major-publishing-actions">
													<?php if($integration_id) { ?>
														<a class="submitdelete deletion" href="javascript:;" id="delete-action">Delete</a>
														<div id="publishing-action">
															<span class="spinner"></span>
															<input name="original_publish" id="original_publish" value="Update" type="hidden">
															<input name="save" class="button button-primary button-large" id="publish" value="Update" type="submit">
														</div>
														<?php } else { ?>
															<div id="publishing-action">
																<span class="spinner"></span>
																<input name="original_publish" id="original_publish" value="Save" type="hidden" />
																<input name="save" class="button button-primary button-large" id="publish" value="Save" type="submit" />

															</div>

															<?php } ?>

															<div class="clear"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
						<script type="text/javascript">
							jQuery(document).ready( function() {
								jQuery('.postbox .hndle, .postbox .handlediv').click( function() {
									jQuery(jQuery(this).parent().get(0)).toggleClass('closed');
								});
								jQuery('#delete-action').on('click', function() {
									if(confirm('Are you sure you want to delete this integration ?')) {
										jQuery('#form_action').val('delete');
										jQuery('#post').submit();
									}
								});
							   jQuery('#status_<?php echo $integration['status']; ?>').prop('checked', true);
							});
						</script>
						<?php
}

function sb_admin_integrations_list_screen_options() {
	if(array_key_exists('page_type', $_GET) && $_GET['page_type'] == 'edit') {
		return;
	}
	$option = 'per_page';
	$args = array(
		'label' => 'Integrations',
		'default' => 30,
		'option' => 'sb_integrations_per_page'
		);
	add_screen_option( $option, $args );
}

function sb_admin_set_option($status, $option, $value) {
	if ( 'sb_integrations_per_page' == $option ) {
		return $value;
	}
	return $status;
}

function sb_admin_handle_post() {
	global $pagenow;
	if($pagenow == 'admin.php' && array_key_exists('page', $_GET) && $_GET['page'] == 'sb_integrations_list' && array_key_exists('page_type', $_GET) && $_GET['page_type'] == 'edit' && array_key_exists('integration_id', $_POST)) {
		if( $_POST['action'] == 'delete') {
			return sb_admin_integration_delete();
		} else {
			return sb_admin_integration_save();
		}
	}
}

function sb_hello_world() {
	return 1;
}

function sb_send_email(WP_REST_Request $request) {
	$json = $request->get_body();
	$data = json_decode($json, 1);
	if(
		false == array_key_exists('emailData', $data) ||
		false == array_key_exists('to', $data['emailData']) ||
		false == array_key_exists('toName', $data['emailData']) ||
		false == array_key_exists('subject', $data['emailData']) ||
		false == array_key_exists('body', $data['emailData'])
		) {
		return false;
	}
	$email_data = $data['emailData'];
	$headers = array();
	if(array_key_exists('from', $email_data) && $email_data['from'] != '') {
		$from_email = $email_data['from'];
	} else {
		$from_email = get_option('woocommerce_email_from_address', '');
	}
	if(array_key_exists('fromName', $email_data) && $email_data['fromName'] != '') {
		$from_name = $email_data['fromName'];
	} else {
		$from_name = get_option('woocommerce_email_from_name', '');
	}
	if($from_email != '') {
		if($from_name != '') {
			$from = $from_name.' <'.$from_email.'>';
		} else {
			$from = $from_email;
		}
		$headers[] = 'From: '.$from;
	}
	if(array_key_exists('cc', $email_data) && $email_data['cc'] != '') {
		$headers[] = 'Cc: '.$email_data['cc'];
	}
	if(array_key_exists('bcc', $email_data) && $email_data['bcc'] != '') {
		$headers[] = 'Bcc: '.$email_data['bcc'];
	}
	if(array_key_exists('toName', $email_data) && $email_data['toName'] != '') {
		$to = $email_data['toName'].' <'.$email_data['to'].'>';
	} else {
		$to = $email_data['to'];
	}
	add_filter( 'wp_mail_content_type', 'sb_set_html_mail_content_type' );
	$email_processed = wp_mail($to, $email_data['subject'], $email_data['body'], $headers);
	remove_filter( 'wp_mail_content_type', 'sb_set_html_mail_content_type' );
	return $email_processed;
}

function sb_get_customer_by_email(WP_REST_Request $request) {
	$email = $request->get_param('email');
	$user = get_user_by_email($email);
	if($user->ID == 0) {return null;}
	$customer = new \Unisho\Sb\CustomerData();
	$customer->extractDataFromWPUser($user);
	return $customer->toArray();
}

function sb_get_customer_by_username(WP_REST_Request $request) {
	$username = $request->get_param('username');
	$user = get_user_by('login', $username);
	if($user->ID == 0) {return null;}
	$customer = new \Unisho\Sb\CustomerData();
	$customer->extractDataFromWPUser($user);
	return $customer->toArray();
}

function sb_check_access(WP_REST_Request $request) {
	$json = $request->get_body();
	$data = json_decode($json, 1);
	if(
		false == array_key_exists('userData', $data) ||
		false == array_key_exists('username', $data['userData']) ||
		false == array_key_exists('password', $data['userData'])
		) {
			return new WP_Error('sb_check_access', 'Wrong json object', array('status' => 400 ));
		//return null;
	}

	$username = $data['userData']['username'];
    $password = $data['userData']['password'];
    
    remove_filter( 'authenticate', 'gglcptch_login_check', 21, 1);
    remove_filter( 'authenticate', 'si_captcha_check_login_captcha', 15);

	$user = wp_authenticate($username, $password);
	
		/*if(is_wp_error($user)) {
		return null;
	}*/
	
	if (is_wp_error($user) && !empty($user->errors)) {
		return new WP_Error('sb_check_access', $user->errors, array('status' => 400 ));
		//return $user->errors;
	}  
	
	$customer = new \Unisho\Sb\CustomerData();
	$customer->setCustomerId($user->ID);
	$customer->setUserName($user->user_login);
	$customer->setEmail($user->user_email);
	$billing_address = new \Unisho\Sb\AddressData();
	$billing_address->extractFromUserBilling($user);
	$shipping_address = new \Unisho\Sb\AddressData();
	$shipping_address->extractFromUserShipping($user);
	if($billing_address->isEmpty() == false) {
		$customer->setBillingAddress($billing_address);
	}
	if($shipping_address->isEmpty() == false) {
		$customer->setShippingAddress($shipping_address);
	}
	return $customer->toArray();
}

function sb_add_customer(WP_REST_Request $request) {
	$json = $request->get_body();
	$data = json_decode($json, 1);
	if(false == array_key_exists('customerData', $data)) {
		return null;
	}
	$customer = new \Unisho\Sb\CustomerData();
	$customer->extractDataFromJson($data['customerData']);
	$username_exists = username_exists($customer->getUserName());
	$user = get_user_by_email($customer->getEmail());
	if($user->ID) {
		$email_exists = true;
	} else {
		$email_exists = false;
	}
	if( false == $username_exists && false == $email_exists ) {
		$user_id = wp_create_user ( $customer->getUserName(), $customer->getPassword(), $customer->getEmail() );
		$user = new WP_User($user_id);
		$billing_address = $customer->getBillingAddress();
		if($billing_address) {
			add_user_meta( $user_id, 'billing_first_name', $billing_address->getFirstName() );
			add_user_meta( $user_id, 'billing_last_name',  $billing_address->getLastName() );
			add_user_meta( $user_id, 'billing_company',    $billing_address->getCompany() );
			add_user_meta( $user_id, 'billing_address_1',  $billing_address->getAddress1() );
			add_user_meta( $user_id, 'billing_address_2',  $billing_address->getAddress2() );
			add_user_meta( $user_id, 'billing_city',       $billing_address->getCity() );
			add_user_meta( $user_id, 'billing_state',      $billing_address->getStateProvinceId() );
			add_user_meta( $user_id, 'billing_country',    $billing_address->getCountryId() );
			add_user_meta( $user_id, 'billing_postcode',   $billing_address->getZipPostalCode() );
			add_user_meta( $user_id, 'billing_email',      $billing_address->getEmail() );
			add_user_meta( $user_id, 'billing_phone',      $billing_address->getPhoneNumber() );
		}

		$shipping_address = $customer->getShippingAddress();
		if($shipping_address) {
			add_user_meta( $user_id, 'shipping_first_name', $shipping_address->getFirstName() );
			add_user_meta( $user_id, 'shipping_last_name',  $shipping_address->getLastName() );
			add_user_meta( $user_id, 'shipping_company',    $shipping_address->getCompany() );
			add_user_meta( $user_id, 'shipping_address_1',  $shipping_address->getAddress1() );
			add_user_meta( $user_id, 'shipping_address_2',  $shipping_address->getAddress2() );
			add_user_meta( $user_id, 'shipping_city',       $shipping_address->getCity() );
			add_user_meta( $user_id, 'shipping_state',      $shipping_address->getStateProvinceId() );
			add_user_meta( $user_id, 'shipping_country',    $shipping_address->getCountryId() );
			add_user_meta( $user_id, 'shipping_postcode',   $shipping_address->getZipPostalCode() );
			add_user_meta( $user_id, 'shipping_email',      $shipping_address->getEmail() );
			add_user_meta( $user_id, 'shipping_phone',      $shipping_address->getPhoneNumber() );
		}
		add_user_meta( $user_id, 'affiliate_id', $customer->getAffiliateId() );
		$user->set_role('customer');
		update_user_meta( $user_id, 'show_admin_bar_front', false );
		$customer->setCustomerId($user_id);
		return $customer->toArray();
	} else {
		$wp_customer = new \Unisho\Sb\CustomerData();
		if($username_exists) {
			$user = get_user_by('login', $customer->getUserName());
		}
		$wp_customer->extractDataFromWPUser($user);
		return $wp_customer->toArray();
	}
	return null;
}

function sb_get_all_countries() {
	$wc_countries = new WC_Countries();
	$country_list = $wc_countries->get_countries();
	$countries = array();

	foreach($country_list as $cid => $cname) {
		$country = new \Unisho\Sb\CountryData();
		$country->setCountryId($cid);
		$country->setName($cname);
		$countries[] = $country->toArray();
	}
	return $countries;
}

function sb_get_all_states() {
	$wc_countries = new WC_Countries();
	$states_countries_list = $wc_countries->get_states();
	$states = array();
	foreach($states_countries_list as $cid => $statess) {
		foreach($statess as $sid => $sname) {
			$state = new \Unisho\Sb\StateData();
			$state->setStateId($sid);
			$state->setStateCode($sid);
			$state->setName($sname);
			$state->setCountryId($cid);
			$states[] = $state->toArray();
		}
	}
	return $states;
}

function sb_get_country_data(WP_REST_Request $request) {
	$countries_to_get = strtolower($request->get_param('countries_to_get'));
	$wc_countries = new WC_Countries();
	$countries = $wc_countries->get_countries();
	$countries_needed = explode(',', $countries_to_get);
	$response = array();
	foreach($countries as $cid => $cname) {
		if(in_array(strtolower($cid), $countries_needed)) {
			$country = new \Unisho\Sb\CountryData();
			$country->setCountryId($cid);
			$country->setName($cname);
			$response[] = $country->toArray();
		}
	}
	return $response;
}
							
function sb_get_next_product(WP_REST_Request $request) {
	$product_id = (int)$request->get_param('product_id');
	global $wpdb;

	$next_product_id = $wpdb->get_var($wpdb->prepare("SELECT `ID` FROM `$wpdb->posts` WHERE (`post_type`='product') AND `ID`>%d AND post_status NOT IN ('draft', 'auto-draft') ORDER BY `ID` ASC", $product_id));
	if($next_product_id) {
		$product = new \Unisho\Sb\ProductVariant($next_product_id);
		if($product->getId()) {
			return $product->toArray();
		} else {
			return null;
		}
	}
	return null;
}

function sb_get_product_variants(WP_REST_Request $request) {
	$product_ids = explode(',', $request->get_param('product_ids'));
	$products = array();
	foreach($product_ids as $product_id) {
		$product = new \Unisho\Sb\ProductVariant((int)$product_id);
		if($product->getId()) {
			$products[] = $product->toArray();
		}
	}
	file_put_contents('request_debug_variants.txt', print_r($products, true));
	return $products;
}

function sb_get_product_variants_by_sku(WP_REST_Request $request) {

	$skus = explode(',', $request->get_param('skus'));
	global $wpdb;
	$placeholders = array_fill(0, count($skus), '%s');
	$product_ids = $wpdb->get_col($wpdb->prepare("SELECT p.`ID` FROM `$wpdb->postmeta` AS pm INNER JOIN `$wpdb->posts` AS p ON p.ID=pm.post_id WHERE pm.meta_key='_sku' AND p.`post_type`='product' AND pm.`meta_value` IN (".implode(',', $placeholders).")", $skus));
	$products = array();
	foreach($product_ids as $product_id) {
		$product = new \Unisho\Sb\ProductVariant((int)$product_id);
		if($product->getId()) {
			$products[] = $product->toArray();
		}
	}
	return $products;
}

function sb_get_order_data(WP_REST_Request $request) {
	$order_ids = $request->get_param('order_ids');
	$order_ids = explode(',', $order_ids);
	$return = array();
	foreach($order_ids as $order_id) {
		$order = new \Unisho\Sb\OrderDataResult($order_id);
		if($order->getOrderId()) {
			$return[] = $order->toArray();
		}
	}
	return $return;
}

function sb_get_next_order_data(WP_REST_Request $request) {
	$order_id = (int)$request->get_param('order_id');
	global $wpdb;

	$next_order_id = $wpdb->get_var($wpdb->prepare("SELECT `ID` FROM `$wpdb->posts` WHERE (`post_type`='shop_order') AND `ID`>%d AND post_status NOT IN ('draft', 'auto-draft') ORDER BY `ID` ASC", $order_id));
	if($next_order_id) {
		$order = new \Unisho\Sb\OrderDataResult($next_order_id);
		return $order->toArray();
	}
	return null;
}

function sb_add_order(WP_REST_Request $request) {
	try {
		file_put_contents('request_debug.txt', print_r($request, true));
		
		$req = $request->get_param('orderData');
		
		$customer_data = $req['Customer'];
		$products_to_add = $req['OrderProductVariants'];
		
		$subtotalAmount = $req['SubtotalAmount'];
		$taxAmount = $req['TaxAmount'];
		$shippingAmount = $req['ShippingAmount'];
		$discountAmount = $req['DiscountAmount']*-1;
		$commissionAmount = $req['CommissionAmount']*-1;
		$hostessRewardAmount = $req['HostessRewardAmount']*-1;
		
		$user_id = absint($customer_data['CustomerId']);
		
		$order = wc_create_order(array('customer_id' => $user_id));
		update_post_meta( $order->id, '_customer_ip_address', $req['CustomerIp'] );

		$addfee = isset($req['CartCalculateTax']) ? $req['CartCalculateTax'] : true;

        if (is_numeric($taxAmount) && $taxAmount > 0)
            $addfee = true; //if we have a specified tax amount, lets go with that and not try to calculate.

		if(false == empty($customer_data['BillingAddress'])) {
			$bill = $customer_data['BillingAddress'];
			$addr = array(
				'first_name' => $bill['FirstName'],
				'last_name' => $bill['LastName'],
				'company' => $bill['Company'],
				'address_1' => $bill['Address1'],
				'address_2' => $bill['Address2'],
				'city' => $bill['City'],
				'state' => $bill['StateProvinceId'],
				'postcode' => $bill['ZipPostalCode'],
				'country' => $bill['CountryId'],
				'email' => $bill['Email'],
				'phone' => $bill['PhoneNumber'],
				);
			$order->set_address($addr, 'billing');
		}
		
		if(false == empty($customer_data['ShippingAddress'])) {
			$ship = $customer_data['ShippingAddress'];
			$addr = array(
				'first_name' => $ship['FirstName'],
				'last_name' => $ship['LastName'],
				'company' => $ship['Company'],
				'address_1' => $ship['Address1'],
				'address_2' => $ship['Address2'],
				'city' => $ship['City'],
				'state' => $ship['StateProvinceId'],
				'postcode' => $ship['ZipPostalCode'],
				'country' => $ship['CountryId'],
				'email' => $ship['Email'],
				'phone' => $ship['PhoneNumber'],
				);
			$order->set_address($addr, 'shipping');
			
			if(true == empty($customer_data['BillingAddress'])) {
				$order->set_address($addr, 'billing');
			}
		}
		
		$product_ids = array();
		$variation_ids = array();
		
		foreach($products_to_add as $prod) {
			$product_factory = new \WC_Product_Factory();
			$product_to_add = $product_factory->get_product($prod['ProductVariantId']);
			$product_ids[] = $prod['ProductVariantId'];
			$args = array();
			if (is_array($prod['Attributes']) || is_object($prod['Attributes'])) {
				foreach($prod['Attributes'] as $attr) {
					if(is_array($attr['AttributeValue'])) {
						if(count($attr['AttributeValue']) == 1) {
							$value = reset($attr['AttributeValue']);
						} else {
							$value = $attr['AttributeValue'];
						}
					} else {
						$value = $attr['AttributeValue'];
					}
					$args['attribute_'.$attr['AttributeId']] = $value;
				}
			}
			if($product_to_add->product_type == 'variable') {
				$variation_id = $product_to_add->get_matching_variation($args);
				$variation_ids[] = $variation_id;
				$product_variation = $product_factory->get_product($variation_id);
				$order->add_product($product_variation, $prod['Quantity'], array('variation' => $args));
			} else {
				$order->add_product($product_to_add, $prod['Quantity']);
			}
		}
		
		if($req['CouponCode'] != '') {
			$order->add_coupon($req['CouponCode']); 
		}
		if($discountAmount <> 0) {
			$discount = new stdClass();
			$discount->name = "Discount";
			$discount->taxable = 0;
			$discount->amount = $discountAmount;
			$discount->tax = 0;
			$discount->tax_data = "";

			$order->add_fee($discount);
		}
		
		if($commissionAmount <> 0) {
			$commission = new stdClass();
			$commission->name = "Commission";
			$commission->taxable = 0;
			$commission->amount = $commissionAmount;
			$commission->tax = 0;
			$commission->tax_data = "";

			$order->add_fee($commission);
		}
		
		if($hostessRewardAmount <> 0) {
			$hostessReward = new stdClass();
			$hostessReward->name = "Hostess Reward";
			$hostessReward->taxable = 0;
			$hostessReward->amount = $hostessRewardAmount;
			$hostessReward->tax = 0;
			$hostessReward->tax_data = "";

			$order->add_fee($hostessReward);
		}
		
		$ship_req_data = array(
			'Customer' => $customer_data,
			'Products' => $products_to_add,
		);
		
		$ship_req = clone $request;
		$ship_req->set_param('shippingRequest', $ship_req_data);
		$shipping_options = sb_get_shipping_options($ship_req);
		$req_shipping_method = $req['ShippingRateName'];
			
		if (empty($req_shipping_method) && count($shipping_options))
		{
			throw new Exception("No shipping method specified and shipping options exist.");
		}
		
		if(count($shipping_options)) {
			foreach ($shipping_options as $shipping_option) {
				if($shipping_option['Name'] == $req_shipping_method) {
					$rate = new WC_Shipping_Rate(
						$shipping_option['Name'],                // id
						$shipping_option['Description'],         // label
						$shippingAmount,                             // cost
						null,                                        // don't know nothing about the taxes
						$shipping_option['Name']                 // method_id
					);
					break;
				}
			}
			$order->add_shipping($rate);
		}		

		$order->calculate_totals();

		if (class_exists('WC_WooTax')) {
			update_post_meta($order->id, '_order_tax', $taxAmount);
			update_post_meta($order->id, '_order_total', $subtotalAmount+$shippingAmount+$taxAmount+$discountAmount);
		}else{
			
			/*$order_tax = get_post_meta( $order->id, '_order_tax', true);

			file_put_contents('request_debug_tax1.txt', print_r($order_tax, true));
			*/

			if(!$addfee){
				$tax_total = $order->get_tax_totals();
				$order->calculate_totals();
			}else{
				$cart_taxes = array();
				$shipping_taxes = array();
				$existing_taxes = $order->get_taxes();
				$saved_rate_ids = array();

				/*file_put_contents('request_debug_existing_tax.txt', print_r($existing_taxes, true));*/
				foreach ($existing_taxes as $tax) {
					// Remove taxes which no longer exist for cart/shipping.
					$order->remove_item($tax->get_id());
					$saved_rate_ids[] = $tax->get_rate_id();
					$tax->set_tax_total(0);
					$tax->set_shipping_tax_total(0);
					$tax->save();
				}

				$order->set_shipping_tax(0);
				$order->set_cart_tax(0);
				$order->save();

				/*file_put_contents('request_debug_existing_tax2.txt', print_r($order->get_taxes(), true));*/

				$fee = new stdClass();
				$fee->name = "Standard Tax";
				$fee->taxable = false;
				$fee->amount = $taxAmount;
				$fee->tax = 0;
				$fee->tax_data = "Standard Tax";

				$order->add_fee( $fee );

				$order->calculate_totals(false);
			}

			/*if(is_array($tax_total) && count($tax_total)){
				$addfee = false;
			}*/
		}

		$order->update_status('processing');
		$order->reduce_order_stock();
	// echo $order->get_total_discount()."\n";
	// var_dump($order);
		return $order->id;
	}
	//catch exception
	catch(Exception $e) {
		$brt_debug_message = 'error creating order: ' .$e->getMessage();
		brt_debug_init('sb_add_order', $brt_debug_message, 1);
		return new WP_Error( 'Plugin Exception', $e->getMessage(), array( 'status' => 400 ) );
	}
}

function sb_estimate_taxes_tmp(WP_REST_Request $request){

	file_put_contents('request_debug_tax.txt', print_r($request, true));

	$customer_data = $request->get_param('customer');
	$products_to_add = $request->get_param('products');
	$shippingInfo = $request->get_param('shipping');
	$shippingAmount = isset($shippingInfo['Rate']) ? floatval($shippingInfo['Rate']) : 0;
	$shippingMethodName = isset($shippingInfo['Name']) ? $shippingInfo['Name'] : '';
	$user_id = absint($customer_data['CustomerId']);
	WC()->cart = new WC_Cart();

	file_put_contents('request_debug_tax1.txt', print_r(WC()->cart, true));
	try{
		$product_ids = array();
		$variation_ids = array();
		if( isset( $products_to_add ) ){
			foreach($products_to_add as $prod) {
				$product_factory = new \WC_Product_Factory();
				$product_to_add = $product_factory->get_product($prod['ProductVariantId']);
				$product_ids[] = $prod['ProductVariantId'];
				$args = array();
				if (is_array($prod['Attributes']) || is_object($prod['Attributes'])) {
					foreach($prod['Attributes'] as $attr) {
						if(is_array($attr['AttributeValue'])) {
							if(count($attr['AttributeValue']) == 1) {
								$value = reset($attr['AttributeValue']);
							} else {
								$value = $attr['AttributeValue'];
							}
						} else {
							$value = $attr['AttributeValue'];
						}
						$args['attribute_'.$attr['AttributeId']] = $value;
					}
				}
				if($product_to_add->product_type == 'variable') {

					$variation_id = $product_to_add->get_matching_variation($args);

					if(!$variation_id){
						$variations = $product_to_add->get_available_variations();

						if(is_array($variations)){
							$variation_id = isset($variations[0]['id']) ? $variations[0]['id'] : 0;
						}
					}

					$variation_ids[] = $variation_id;
					$product_variation = $product_factory->get_product($variation_id);

					file_put_contents('request_debug_taxvar3.txt', print_r($product_variation, true));
					
					WC()->cart->add_to_cart($product_to_add->get_id(), $prod['Quantity'], $product_variation->get_id(), $args);
				} else {

					WC()->cart->add_to_cart($product_to_add->get_id(), $prod['Quantity']);
				}
			}
		}
	}
	catch (Exception $ex){
		file_put_contents('request_debug_taxvar4.txt', print_r($ex, true));
	}

	/*$chosen_shipping_methods = array($shippingMethodName);

	WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_methods );*/

	file_put_contents('request_debug_tax2.txt', '2 working now');

	WC()->customer = new WC_Customer( $user_id );
	WC()->customer->set_props( array(
		'billing_country'   => $bill['CountryId'],
		'billing_state'     => $bill['StateProvinceId'],
		'billing_postcode'  => $bill['ZipPostalCode'],
		'billing_city'      => $bill['City'],
		'billing_address_1' => $bill['Address1'],
		'billing_address_2' => $bill['Address2'],
	) );

	if ( wc_ship_to_billing_address_only() ) {
		WC()->customer->set_props( array(
			'shipping_country'   => $ship['CountryId'],
			'shipping_state'     => $ship['StateProvinceId'],
			'shipping_postcode'  => $ship['ZipPostalCode'],
			'shipping_city'      => $ship['City'],
			'shipping_address_1' => $ship['Address1'],
			'shipping_address_2' => $ship['Address2'],
		) );
		if ( ! empty( $ship['CountryId'] ) ) {
			WC()->customer->set_calculated_shipping( true );
		}
	} else {
		WC()->customer->set_props( array(
			'shipping_country'   => $ship['CountryId'],
			'shipping_state'     => $ship['StateProvinceId'],
			'shipping_postcode'  => $ship['ZipPostalCode'],
			'shipping_city'      => $ship['City'],
			'shipping_address_1' => $ship['Address1'],
			'shipping_address_2' => $ship['Address2'],
		) );
		if ( ! empty( $ship['CountryId'] ) ) {
			WC()->customer->set_calculated_shipping( true );
		}
	}

	$shipping = WC()->shipping();
	$shipping_packages = WC()->cart->get_shipping_packages();

	if(false == empty($customer_data['ShippingAddress'])) {
		$ship = $customer_data['ShippingAddress'];
		foreach($shipping_packages as &$sp) {
			$sp['destination']['country'] = $ship['CountryId'];
			$sp['destination']['state'] = $ship['StateProvinceId'];
			$sp['destination']['postcode'] = $ship['ZipPostalCode'];
			$sp['destination']['city'] = $ship['City'];
			$sp['destination']['address'] = $ship['Address1'];
			$sp['destination']['address_2'] = $ship['Address2'];
		}
	}
	$shipping->calculate_shipping($shipping_packages);
	$package = reset($shipping->get_packages());

	file_put_contents('request_debug_tax3.txt', print_r($package, true));
	
	$shipping_tax = 0;
	$rates = isset($package['rates']) ? $package['rates'] : array();
	if(is_array($rates) && count($rates)){
		foreach($rates as $rateid => $rate_data){
			$shipping_taxes = isset($rate_data->taxes) ? $rate_data->taxes : array();
			if(is_array($shipping_taxes) && count($shipping_taxes)){
				foreach($shipping_taxes as $tax_id => $tax_amount){
					$shipping_tax += $tax_amount;
				}
			}
		}
	}

	file_put_contents('request_debug_tax4.txt', print_r($shipping_tax, true));

	/*if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) :

		$packages = WC()->shipping->get_packages();
		file_put_contents('request_debug_tax_pckg.txt', print_r($packages, true));

	endif;*/
	
	WC()->customer->save();
	WC()->cart->calculate_totals();

	file_put_contents('request_debug_tax5.txt', print_r(WC()->cart, true) );

	$tax_rate = $shipping_tax;

	file_put_contents('request_debug_tax.txt', print_r(WC()->cart->get_tax_totals(), true));
	if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :

		if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) :

			foreach ( WC()->cart->get_tax_totals() as $code => $tax ) :

				$tax_rate += $tax->amount;

			endforeach;

		endif;

	endif;

	file_put_contents('request_debug_tax6.txt', print_r($tax_rate, true));

	return $tax_rate;
}

	function sb_estimate_taxes(WP_REST_Request $request) {
		file_put_contents('request_debug_tax.txt', print_r($request, true));
		$customer_data = $request->get_param('customer');
		$products_to_add = $request->get_param('products');
		//added by kanha..
		$shippingInfo = $request->get_param('shipping');
		//$shippingAmount = $request->get_param('ShippingAmount');
		$shippingAmount = isset($shippingInfo['Rate']) ? floatval($shippingInfo['Rate']) : 0;
		$shippingMethodName = isset($shippingInfo['Name']) ? $shippingInfo['Name'] : '';
		$user_id = absint($customer_data['CustomerId']);
		$order = wc_create_order(array('customer_id' => $user_id));
		update_post_meta( $order->id, '_customer_ip_address', $req['CustomerIp'] );
		$cart = WC()->cart = new WC_Cart();
		$customer = WC()->customer = new WC_Customer();
        WC()->frontend_includes();
        WC()->session = new WC_Session_Handler();
		if(false == empty($customer_data['BillingAddress'])) {
			$bill = $customer_data['BillingAddress'];
			$addr = array(
				'first_name' => $bill['FirstName'],
				'last_name' => $bill['LastName'],
				'company' => $bill['Company'],
				'address_1' => $bill['Address1'],
				'address_2' => $bill['Address2'],
				'city' => $bill['City'],
				'state' => $bill['StateProvinceId'],
				'postcode' => $bill['ZipPostalCode'],
				'country' => $bill['CountryId'],
				'email' => $bill['Email'],
				'phone' => $bill['PhoneNumber'],
				);
			$order->set_address($addr, 'billing');
			if ( class_exists( 'WC_WooTax' ) ) {
				$customer->set_address($bill['Address1']);
				$customer->set_address_2($bill['Address2']);
				$customer->set_city($bill['City']);
				$customer->set_state($bill['StateProvinceId']);
				$customer->set_postcode($bill['ZipPostalCode']);
				$customer->set_country($bill['CountryId']);
			}
		}
		if(false == empty($customer_data['ShippingAddress'])) {
		$ship = $customer_data['ShippingAddress'];
		$addr = array(
			'first_name' => $ship['FirstName'],
			'last_name' => $ship['LastName'],
			'company' => $ship['Company'],
			'address_1' => $ship['Address1'],
			'address_2' => $ship['Address2'],
			'city' => $ship['City'],
			'state' => $ship['StateProvinceId'],
			'postcode' => $ship['ZipPostalCode'],
			'country' => $ship['CountryId'],
			'email' => $ship['Email'],
			'phone' => $ship['PhoneNumber'],
			);
		$order->set_address($addr, 'shipping');
		$order->set_address($addr, 'billing');
		if ( class_exists( 'WC_WooTax' ) ) {
			$customer->set_shipping_address($ship['Address1']);
			$customer->set_shipping_address_2($ship['Address2']);
			$customer->set_shipping_city($ship['City']);
			$customer->set_shipping_state($ship['StateProvinceId']);
			$customer->set_shipping_postcode($ship['ZipPostalCode']);
			$customer->set_shipping_country($ship['CountryId']);
			if (empty($customer_data['BillingAddress'])) {
			$customer->set_address($ship['Address1']);
			$customer->set_address_2($ship['Address2']);
			$customer->set_city($ship['City']);
			$customer->set_state($ship['StateProvinceId']);
			$customer->set_postcode($ship['ZipPostalCode']);
			$customer->set_country($ship['CountryId']);
		}
	}
	}
	$product_ids = array();
	$variation_ids = array();
	if( isset( $products_to_add ) ){
	foreach($products_to_add as $prod) {
		$product_factory = new \WC_Product_Factory();
		$product_to_add = $product_factory->get_product($prod['ProductVariantId']);
		$product_ids[] = $prod['ProductVariantId'];
		$args = array();
		if (is_array($prod['Attributes']) || is_object($prod['Attributes'])) {
			foreach($prod['Attributes'] as $attr) {
				if(is_array($attr['AttributeValue'])) {
					if(count($attr['AttributeValue']) == 1) {
						$value = reset($attr['AttributeValue']);
					} else {
						$value = $attr['AttributeValue'];
					}
				} else {
					$value = $attr['AttributeValue'];
				}
				$args['attribute_'.$attr['AttributeId']] = $value;
			}
		}
		if($product_to_add->product_type == 'variable') {
			$variation_id = $product_to_add->get_matching_variation($args);
			$variation_ids[] = $variation_id;
			$product_variation = $product_factory->get_product($variation_id);
			$order->add_product($product_variation, $prod['Quantity'], array('variation' => $args));
			$cart->add_to_cart($product_to_add->get_id(), $prod['Quantity'], $product_variation->get_id(), $args);
		} else {
			$order->add_product($product_to_add, $prod['Quantity']);
			$cart->add_to_cart($product_to_add->get_id(), $prod['Quantity']);
		}
	}
	}
	//$shippingId = "flat_rate:1";
	if( !empty($shippingMethodName) && $shippingMethodName != '' ) {
	$shipping = WC()->shipping();
	$shipping_packages = $cart->get_shipping_packages();
	if(false == empty($customer_data['ShippingAddress'])) {
		$ship = $customer_data['ShippingAddress'];
		if( isset( $shipping_packages ) ){
			foreach($shipping_packages as &$sp) {
				$sp['destination']['country'] = $ship['CountryId'];
				$sp['destination']['state'] = $ship['StateProvinceId'];
				$sp['destination']['postcode'] = $ship['ZipPostalCode'];
				$sp['destination']['city'] = $ship['City'];
				$sp['destination']['address'] = $ship['Address1'];
				$sp['destination']['address_2'] = $ship['Address2'];
			}
		}
	}
	$shipping->calculate_shipping($shipping_packages);
	$packages = reset($shipping->get_packages());
	if( isset( $packages ) ){
		foreach ( $packages as $package_key => $tmp_package ) {
			file_put_contents('request_debug_tax.txt', print_r($tmp_package, true));
			if ( isset( $tmp_package['rates'][ $shippingMethodName ] )  ) {
				$item_id = $order->add_shipping( $tmp_package['rates'][ $shippingMethodName ] );
			}else if( isset($tmp_package[$shippingMethodName])){
				$item_id = $order->add_shipping( $tmp_package[ $shippingMethodName ] );
			}
		}
	}
	} else {
	$ship_req = clone $request;
	$ship_req_data = array(
		'Customer' => $customer_data,
		'Products' => $products_to_add,
		);
	$ship_req->set_param('shippingRequest', $ship_req_data);
	$shipping_options = sb_get_shipping_options($ship_req);
	if(count($shipping_options)) {
		$rate = new WC_Shipping_Rate(
		$shipping_options[0]['Name'],                // id
		$shipping_options[0]['Description'],         // label
		// $shipping_options[0]['Rate'],                // cost
		$req['ShippingAmount'],                      // cost
		null,                                        // don't know nothing about the taxes
		$shipping_options[0]['Name']                 // method_id
		);
		$order->add_shipping($rate);
		}
	}
	// add the actual shipping

	if ( class_exists( 'WC_WooTax' ) ) {
		$wt_cart = new WC_WooTax_Checkout($cart);
		$tax = $cart->tax_total;
	} else {
		$order->calculate_totals();
		$tax = $order->get_total_tax();
	}

	// $order->cancel_order();
	foreach($order->get_items() as $item_id => $item) {
		wc_delete_order_item($item_id);
	}
	wp_delete_post($order->id);

	if (is_null($tax) || !isset($tax))
		$tax = 0;

	return $tax;
}

function sb_get_order_shipping_data(WP_REST_Request $request) {
	$order_id = $request->get_param('order_id');
	$order = new \Unisho\Sb\OrderDataResult($order_id);
	if($order->getOrderId() == 0 || $order->getOrderId() != $order_id) {
		return null;
	}
	$customer = $order->getCustomer();
	$wc_order = $order->getWCOrder();
	$shipping_methods_raw = $wc_order->get_shipping_methods();
	$shipping_methods = array();
	foreach($shipping_methods_raw as $sm) {
		$shipping_methods[] = $sm['method_id'];
	}
	$shipping_method = implode(',', $shipping_methods);
	$return = array(
		'OrderId' => $order->getOrderId(),
		'CustomerId' => $customer->getCustomerId(),
		'ShippingMethod' => $shipping_method,
		'ShippingAddress' => $customer->getShippingAddress()->toArray(),
		'Shipments' => array()
		);
	return $return;
}

function sb_get_shipping_options(WP_REST_Request $request) {
	$req = $request->get_param('shippingRequest');
	if ($req == null)
		return $request;
	if(array_key_exists('Products', $req) == false) {
		return null;
	}
	if(array_key_exists('Customer', $req)) {
		$customer_data = $req['Customer'];
	} else {
		$customer_data = array('ShippingAddress' => array());
	}
	$products_to_add = $req['Products'];
	if(is_array($products_to_add) == false && count($products_to_add) < 1) {
		return null;
	}
	$user_id = absint($customer_data['CustomerId']);
	//$cart = WC()->cart;

    $cart = new WC_Cart();
    $session = new WC_Session_Handler();
    $customer = new WC_Customer();

    WC()->cart = $cart;
    WC()->session = $session;
    WC()->customer = $customer;
    WC()->frontend_includes();

	$cart->empty_cart();
	$product_ids = array();
	$variation_ids = array();
	$only_virtual_products = true;

	foreach($products_to_add as $prod) {
		$product_factory = new \WC_Product_Factory();
		$product_id = $prod['ProductVariantId'];
		$product_to_add = $product_factory->get_product($product_id);

		if(false==$product_to_add->is_virtual()) {
			$only_virtual_products = false;
			$product_ids[] = $product_id;
			$args = array();
			if (is_array($prod['Attributes']) || is_object($prod['Attributes'])) {
				foreach($prod['Attributes'] as $attr) {
					if(is_array($attr['AttributeValue'])) {
						if(count($attr['AttributeValue']) == 1) {
							$value = reset($attr['AttributeValue']);
						} else {
							$value = $attr['AttributeValue'];
						}
					} else {
						$value = $attr['AttributeValue'];
					}
					$args['attribute_'.$attr['AttributeId']] = $value;
				}
			}
			if($product_to_add->product_type == 'variable') {
				$variation_id = $product_to_add->get_matching_variation($args);
				$variation_ids[] = $variation_id;
				$product_variation = $product_factory->get_product($variation_id);
				$cart->add_to_cart($product_id, $prod['Quantity'], $variation_id, $args);
			} else {
				$cart->add_to_cart($product_id, $prod['Quantity']);
			}
		}
	}
	$shipping = WC()->shipping();
	$shipping_packages = $cart->get_shipping_packages();
	if(false == empty($customer_data['ShippingAddress'])) {
		$ship = $customer_data['ShippingAddress'];
		foreach($shipping_packages as &$sp) {
			$sp['destination']['country'] = $ship['CountryId'];
			$sp['destination']['state'] = $ship['StateProvinceId'];
			$sp['destination']['postcode'] = $ship['ZipPostalCode'];
			$sp['destination']['city'] = $ship['City'];
			$sp['destination']['address'] = $ship['Address1'];
			$sp['destination']['address_2'] = $ship['Address2'];
		}
	}
	$shipping->calculate_shipping($shipping_packages);
	$package = reset($shipping->get_packages());
	$rates = $package['rates'];
	file_put_contents('request_debug_ship.txt', print_r($rates, true));
	$result = array();
	if(false==$only_virtual_products) {
		foreach($rates as $rate) {
			$result[] = array(
				'Rate' => $rate->cost,
				'Name' => $rate->id,
				'Description' => $rate->label
				);
		}
	}
	return $result;
}

function sb_apply_discount(WP_REST_Request $request) {
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		$postresource = fopen("php://input", "r");
		while ($postData = fread($postresource, 1024)) {
			$input_json .= $postData;
		}
		fclose($postresource);
	}

	if (isset($input_json)) {
		$data = json_decode($input_json, 1);
		$code = $data['couponCode'];
	}

	$coupon = new \WC_Coupon($code);
	if($coupon->id == 0) {
		return null;
	}
	$return = array(
		'Id' => $coupon->id,
		'coupon' => $code,
		);
	if(sb_coupon_is_valid($coupon)) {
		$return['success'] = true;
	} else {
		$return['success'] = false;
	}
	$cp = new \Unisho\Sb\DiscountData();
	$cp->extractFromWcCoupon($coupon);
	$return['discount'] = $cp->toArray();
	return $return;
}

function sb_coupon_is_valid($coupon, $subtotal = null, $product_ids = null, $variation_ids = null, $user_id = null) {
	// function compiled from the functions in WC_Coupon
	// it does the same checks, but on the supplied $product_ids and $user_id,
	// instead of on the cart or on the currently logged in user

	if(!$coupon->exists) {return false;}
	
	if($coupon->expiry_date && current_time('timestamp') > $coupon->expiry_date) {return false;}
	
	if($coupon->usage_limit > 0 && $coupon->usage_count >= $coupon->usage_limit) {return false;}
	
	if($subtotal === null && $product_ids === null && $variation_ids === null && $user_id === null) {
		return true;
	}
	
	if($coupon->usage_limit_per_user > 0) {
		global $wpdb;
		$user_id = absint($user_id);
		$usage_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT( meta_id ) FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_used_by' AND meta_value = %d;", $coupon->id, $user_id));
		if($usage_count >= $coupon->usage_limit_per_user) {
			return false;
		}
	}

	// minimum amount
	if($coupon->minimum_amount > 0 && wc_format_decimal($coupon->minimum_amount) > wc_format_decimal($subtotal)) {
		return false;
	}
	// maximum amount
	if($coupon->maximum_amount > 0 && wc_format_decimal($coupon->maximum_amount) < wc_format_decimal($subtotal)) {
		return false;
	}
	echo "has ok maximum amount\n";
	$all_ids = array_merge($product_ids, $variation_ids);
	// product ids
	if(sizeof($coupon->product_ids) > 0) {
		$valid_for_cart = false;
		if( isset( $all_ids ) ){
		foreach($all_ids as $pid) {
			if(in_array($pid, $coupon->product_ids)) {
				$valid_for_cart = true;
				break;
			}
		}
		}
		if(!$valid_for_cart) {
			return false;
		}
	}

	// product categories and excluded categories
	if(sizeof($coupon->product_categories) > 0 || sizeof($coupon->exclude_product_categories) > 0) {
		$valid_for_cats = false;
		$valid_for_excluded_cats = false;
		foreach($product_ids as $pid) {
			$product_cats = wc_get_product_cat_ids($pid);
			// If we find an item with a cat in our allowed cat list, the coupon is valid
			if(sizeof(array_intersect($product_cats, $coupon->product_categories)) > 0) {
				$valid_for_cats = true;
			}
			// If we find an item with a cat NOT in our disallowed cat list, the coupon is valid
			if(empty($product_cats) || sizeof(array_diff($product_cats, $coupon->exclude_product_categories)) > 0) {
				$valid_for_excluded_cats = true;
			}
		}
		if(!$valid_for_cats || !$valid_for_excluded_cats) {
			return false;
		}
	}

	// sale items
	if('yes' === $coupon->exclude_sale_items && $coupon->is_type(wc_get_product_coupon_types())) {
		$valid_for_cart      = false;
		$product_ids_on_sale = wc_get_product_ids_on_sale();
		foreach($all_ids as $pid) {
			if(!in_array($pid, $product_ids_on_sale, true)) {
				$valid_for_cart = true;
				break;
			}
		}
		if(!$valid_for_cart) {
			return false;
		}
	}
	if(!$coupon->is_type(wc_get_product_coupon_types())) {
		// excluded products
		if(sizeof($coupon->exclude_product_ids) > 0) {
			$valid_for_cart = true;
			foreach($all_ids as $pid) {
				if(in_array($pid, $coupon->exclude_product_ids)) {
					$valid_for_cart = false;
					break;
				}
			}
			if(!$valid_for_cart) {
				return false;
			}
		}
		// excluded sale items
		if($coupon->exclude_sale_items == 'yes') {
			$valid_for_cart = true;
			$product_ids_on_sale = wc_get_product_ids_on_sale();
			foreach($all_ids as $pid) {
				if(in_array($pid, $product_ids_on_sale, true)) {
					$valid_for_cart = false;
					break;
				}
			}
			if(!$valid_for_cart) {
				return false;
			}
		}
	}

	return true;
}

function sb_add_order_note(WP_REST_Request $request) {
	$req = $request->get_param('noteRequest');
	$order_id = (int)$req['OrderId'];
	if($order_id < 1) {
		return null;
	}
	$order_factory = new \WC_Order_Factory();
	$order = $order_factory->get_order($order_id);
	if($order->post->ID != $order_id) {
		return null;
	}
	$note = $req['Note'];
	$for_customer = (int)$req['DisplayToCustomer'];
	$x = $order->add_order_note($note, $for_customer, false);
	return $x;
}

function sb_get_discount_codes(WP_REST_Request $request) {
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		$postresource = fopen("php://input", "r");
		while ($postData = fread($postresource, 1024)) {
			$input_json .= $postData;
		}
		fclose($postresource);
	}

	if (isset($input_json)) {
		$data = json_decode($input_json, 1);
		$codes = $data['codes'];
	}
	
	$coupons = array();
	foreach($codes as $code) {
		$coupon = new \WC_Coupon($code);
		if($coupon->exists) {
			$cp = new \Unisho\Sb\DiscountData();
			$cp->extractFromWcCoupon($coupon);
			$coupons[] = $cp->toArray();
		}
	}
	return $coupons;
}

function sb_extract_address_fields($post, $type = 'billing') {
	if(is_int($post)) {
		$post = get_post($post);
	}
	if($post instanceof WP_Post == false || $post->ID == null) {
		return array();
	}
	$fields = array(
		'first_name' => 'FirstName',
		'last_name'  => 'LastName',
		'company'    => 'Company',
		'email'      => 'Email',
		'phone'      => 'PhoneNumber',
		'country'    => 'CountryId',
		'address_1'  => 'Address1',
		'address_2'  => 'Address2',
		'city'       => 'City',
		'state'      => 'StateProvinceId',
		'postcode'   => 'ZipPostalCode'
		);
	$result = array();
	foreach($fields as $wp_meta_key => $target_key) {
		$k = '_'.$type.'_'.$wp_meta_key; // eg _billing_first_name
		$result[$target_key] = $post->$k;
	}
	return $result;
}

function sb_arg_is_email($param, $request, $key) {
	return is_email($param);
}

function sb_set_html_mail_content_type() {
	return 'text/html';
}

function sb_check_auth() {
	if(array_key_exists('HTTP_X_GUID', $_SERVER) == false && array_key_exists('guid', $_GET) == false) {
		return false;
	}
	$key = $_SERVER['HTTP_X_GUID'];
	if($key == '') {
		$key = sanitize_text_field( $_GET['guid'] );
	}
	global $wpdb;
	$integration_id = $wpdb->get_var($wpdb->prepare('SELECT integration_id FROM '.$wpdb->prefix.'sb_integrations WHERE guid=%s AND status=1', $key));
	if($integration_id) {
		return true;
	}
	return false;
}

function sb_get_shopping_with() {
	if (isset($_COOKIE['sb_cookie_affiliate']))
	{
		list($a, $b, $c, $d, $e) = explode('|', $_COOKIE["sb_cookie_affiliate"]);
		$result_json = array('AffiliateId' => $a, 'Name' => $b, 'City' => $c, 'PhoneNumber' => $d, 'Email' => $e);
	}
	else
	{
		$result_json = array('AffiliateId' => 0);
	}
	return $result_json;
}

function sb_set_shopping_with(WP_REST_Request $request) {
	$username = $request->get_param('username');
	return sb_set_shopping_with_exec($username, false);
}

function sb_set_shopping_with_exec($username, $userid) {
	if ($userid)
		$affiliate = get_basic_affiliate_by_id($username);
	else
		$affiliate = get_basic_affiliate_info($username);
	if ($affiliate->AffiliateId != '')
	{
		$mystring = $affiliate->AffiliateId . '|';
		$mystring = $mystring . str_replace('|', '', $affiliate->FirstName . ' ' . $affiliate->LastName) . '|';
		$mystring = $mystring . str_replace('|', '', $affiliate->City) . '|';
		$mystring = $mystring . str_replace('|', '', $affiliate->PhoneNumber) . '|';
		$mystring = $mystring . str_replace('|', '', $affiliate->Email);
		setcookie('sb_cookie_affiliate', $mystring, time() + (86400 * 270), "/"); //270 days
		setcookie('socialbug_affiliate_id', $affiliate->AffiliateId, time() + (86400 * 270), "/"); //270 days
		$result_json = $affiliate;
	}
	else
	{
		$result_json = array('AffiliateId' => 0);
	}
	return $result_json;
}

add_action('rest_api_init', function () {
	$namespace = 'socialbug/v1';
	// misc methods
	register_rest_route( $namespace, '/HelloWorld', array(
		'methods' => 'GET',
		'callback' => 'sb_hello_world',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/SendEmail', array(
		'methods' => 'POST',
		'callback' => 'sb_send_email',
		'permission_callback' => 'sb_check_auth',
		));
	// customer methods
	register_rest_route( $namespace, '/GetCustomerByEmail/(?P<email>.*)', array(
		'methods' => 'GET',
		'callback' => 'sb_get_customer_by_email',
		'args' => array(
			'email' => array(
				'validate_callback' => 'sb_arg_is_email'
				)
			),
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetCustomerByEmail', array(
		'methods' => 'POST',
		'callback' => 'sb_get_customer_by_email',
		'args' => array(
			'email' => array(
				'validate_callback' => 'sb_arg_is_email'
				)
			),
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetCustomerByUsername/(?P<username>.*)', array(
		'methods' => 'GET',
		'callback' => 'sb_get_customer_by_username',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/AddCustomer', array(
		'methods' => 'POST',
		'callback' => 'sb_add_customer',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/CheckAccess', array(
		'methods' => 'POST',
		'callback' => 'sb_check_access',
		'permission_callback' => 'sb_check_auth',
		));
	// state+country methods
	register_rest_route( $namespace, '/GetAllCountries', array(
		'methods' => 'GET',
		'callback' => 'sb_get_all_countries',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetAllStates', array(
		'methods' => 'GET',
		'callback' => 'sb_get_all_states',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetCountryData/(?P<countries_to_get>.+)', array(
		'methods' => 'GET',
		'callback' => 'sb_get_country_data',
		'permission_callback' => 'sb_check_auth',
		));
	// products methods
	register_rest_route( $namespace, '/GetNextProductVariant/(?P<product_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'sb_get_next_product',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetProductVariants/(?P<product_ids>[0-9,]+)', array(
		'methods' => 'GET',
		'callback' => 'sb_get_product_variants',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetProductVariantsBySKU/(?P<skus>.+)', array(
		'methods' => 'GET',
		'callback' => 'sb_get_product_variants_by_sku',
		'permission_callback' => 'sb_check_auth',
		));
	// order sales methods
	register_rest_route( $namespace, '/GetOrderData/(?P<order_ids>[0-9,]+)', array(
		'methods' => 'GET',
		'callback' => 'sb_get_order_data',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetNextOrderData/(?P<order_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'sb_get_next_order_data',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/AddOrder', array(
		'methods' => 'POST',
		'callback' => 'sb_add_order',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/AddOrderNote', array(
		'methods' => 'POST',
		'callback' => 'sb_add_order_note',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetDiscountCodes', array(
		'methods' => 'POST',
		'callback' => 'sb_get_discount_codes',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/ApplyDiscount', array(
		'methods' => 'POST',
		'callback' => 'sb_apply_discount',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/EstimateTaxes', array(
		'methods' => 'POST',
		'callback' => 'sb_estimate_taxes',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetOrderShippingData/(?P<order_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'sb_get_order_shipping_data',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetShippingOptions', array(
		'methods' => 'POST',
		'callback' => 'sb_get_shipping_options',
		'permission_callback' => 'sb_check_auth',
		));
	register_rest_route( $namespace, '/GetShoppingWith', array(
		'methods' => 'GET',
		'callback' => 'sb_get_shopping_with',
		));
	register_rest_route( $namespace, '/SetShoppingWith/(?P<username>.+)', array(
		'methods' => 'GET',
		'callback' => 'sb_set_shopping_with',
		));
});

// Affiliate area!
/*Cristi test these functions below
1) sb_check_affiliate_code_exists
	this function was updated to handle ConsultantId and also to make use of sb_set_affiliate_id
*/
function sb_check_affiliate_code_exists() {
			if(array_key_exists('AffiliateId', $_GET) || array_key_exists('ConsultantId', $_GET)) {
				$brt_debug_message = "affiliate id found in query string. ";
				if(array_key_exists('AffiliateId', $_GET))
					$affiliate_id = absint($_GET['AffiliateId']);
				else if (array_key_exists('ConsultantId', $_GET))
					$affiliate_id = absint($_GET['ConsultantId']);
			$affiliate = get_user_by('id', $affiliate_id); //get user object from affiliate id
			if($affiliate->ID != null) { //stop if we don't find anything
			$brt_debug_message .= "User found in system.";
			$user_id = get_current_user_id();
				if($user_id != $affiliate_id) { // a user can't affiliate themselves
				if($user_id) {
					$brt_debug_message .= "Current user id is NOT NULL. ";
					update_user_meta($user_id, 'affiliate_id', $affiliate_id);
				} else {
					$brt_debug_message .= "Current user id is NULL. ";
					sb_set_affiliate_id($affiliate_id);
				}
			}
		}else {
			$brt_debug_message = "affiliate id was detected but is invalid. no cusotmer exists in wp with this id";
		}
		brt_debug_init('sb_check_affiliate_code_exists',$brt_debug_message, sanitize_text_field( $_GET['AffiliateId'] ) );
	}
}

function brt_debug_init($function,$message,$affiliate_id){
	//disable james debug log.
	return;
	if ($affiliate_id){
		global $wpdb;
		$post_id = 0;
		$sql = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_title = '%d' AND post_status = 'publish' AND post_type = 'debug_log'" , $affiliate_id);
		if ($results = $wpdb->get_results($sql)){
			$post_id = $results[0]->ID;
		}
		if ($post_id > 0){
			update_post_meta($post_id,'affiliate_id',$affiliate_id);
			$data = array(
				'comment_post_ID' =>    $post_id,
				'comment_content' =>    "DEBUG ". $function ."():" . $message,
				'comment_author_IP' =>  $_SERVER['REMOTE_ADDR']
				);
			wp_insert_comment($data);
			$comment_ID = $wpdb->insert_id;
			update_comment_meta($comment_ID,'affiliate_id',$affiliate_id);
		} else {
			$mypost = array(
				'post_title' =>  $affiliate_id,
				'post_content' => "",
				'post_status' =>    "publish",
				'post_type' =>  "debug_log"
				);
			wp_insert_post($mypost);
			$post_id = $wpdb->insert_id;
			add_post_meta($post_id,'affiliate_id',$affiliate_id);
			$data = array(
				'comment_post_ID' =>    $post_id,
				'comment_content' =>    "DEBUG ". $function ."(): " . $message,
				'comment_author_IP' =>  $_SERVER['REMOTE_ADDR']
				);
			wp_insert_comment($data);
		}
	}
}

function sb_on_user_login($user_login, $user) {
	$affiliate_id = sb_get_affiliate_id();
	if($affiliate_id > 0) {
		$user_id = $user->ID;
		update_user_meta($user_id, 'affiliate_id', $affiliate_id);
		unset($wp_session['socialbug_affiliate_id']);
	}
}

function sb_on_user_register($user_id) {
	$affiliate_id = sb_get_affiliate_id();
	if($affiliate_id > 0) {
		update_user_meta($user_id, 'affiliate_id', $affiliate_id);
		unset($wp_session['socialbug_affiliate_id']);
	}
}

function sb_on_place_order($post_id, $something, $update) {
	if($update === false) {
		$affiliate_id = null;
		$user_id = get_current_user_id();
		if($user_id) {
			$affiliate_id = get_user_meta($user_id, 'affiliate_id', true);
		} else {
			$affiliate_id = sb_get_affiliate_id();
		}
		if($affiliate_id && $affiliate_id > 0) {
			update_post_meta($post_id, 'affiliate_id', $affiliate_id);
		}
	}
}

add_action('wp_loaded', 'sb_check_affiliate_code_exists');
add_action('wp_login', 'sb_on_user_login', 15, 2);
add_action('wp_register', 'sb_on_user_register');
add_action('woocommerce_created_customer', 'sb_on_user_register');
add_action('save_post_shop_order', 'sb_on_place_order', 15, 3);
// END Affiliate area
add_action( 'admin_init', 'sb_admin_handle_post' );
add_action( 'admin_menu', 'sb_admin_menu' );
add_filter( 'set-screen-option', 'sb_admin_set_option', 112, 3);
register_activation_hook( AFFILIATE_USERS_PAGE, 'sb_create_table' );
/*Cristi test these functions below
1) sb_set_affiliate_id
    this function will set the affiliate id if we have no wp user. E.g (anonymous user)
    I am saving the user id in 3 places. I am doing this because some clients have complained about
    affiliates urls not working. This issue happens sometimes. To prevent this issue from happening, I have
    decided to save the affiliate id in multiple places. Then when I need it, I can check if exists in any of
    the places. Hopefully this offers us a fail safe.
    $wp_session / $_SESSION / socialbug_affiliate_id (cookie)
2) sb_get_affiliate_id
    the code checks for an affiliate id in the following places
    $wp_session / $_SESSION / socialbug_affiliate_id (cookie) / sb_cookie_affiliate (cookie)
    if the code finds the affiliate id in one of the plaes, then the code does not check the other places
3) sb_persist_session_with_cookie
    some hosting companies use very heavy caching on their servers and thus do not allow use of sessions on most pages.
    To get around this we are resetting all session data.
    First code checks if we have shopping with from cookie sb_cookie_affiliate (cookie)
    if it does not have this data then we will check if we have
    data in cookies sb_cookie_affiliate_username / socialbug_affiliate_id
    if we have data in those cookies and we did not have data in sb_cookie_affiliate then we wil try and call server
    and get data to populate the sb_cookie_affiliate
    this is called on wp init (this code should be quick as it only calls server once if cookie not set)
*/
function sb_set_affiliate_id($affiliate_id) {
	$brt_debug_message = "setting affiliate id " . $affiliate_id;
	if(class_exists('WP_Session')) {
		$wp_session = WP_Session::get_instance();
		$wp_session['socialbug_affiliate_id'] = $affiliate_id;
	}
	if(is_array($_SESSION)) {
		$_SESSION['socialbug_affiliate_id'] = $affiliate_id;
	}
//always set a cookie
	setcookie('socialbug_affiliate_id', $affiliate_id, time() + (86400 * 270), "/"); //270 days
	brt_debug_init('sb_set_affiliate_id', $brt_debug_message, $affiliate_id);
}

function sb_get_affiliate_id() {
	$brt_debug_message = "getting affiliate id ";
	$affiliate_id = 0;
	if(class_exists('WP_Session')) {
		$wp_session = WP_Session::get_instance();
		if ($wp_session['socialbug_affiliate_id'] != null && $wp_session['socialbug_affiliate_id'] > 0) {
			$affiliate_id = $wp_session['socialbug_affiliate_id'];
			$brt_debug_message = $brt_debug_message . $affiliate_id . " from wp_session";
		}
	}

	if($affiliate_id == 0 && is_array($_SESSION) && $_SESSION['socialbug_affiliate_id'] != null && $_SESSION['socialbug_affiliate_id'] > 0) {
		$affiliate_id = $_SESSION['socialbug_affiliate_id'];
		$brt_debug_message = $brt_debug_message . $affiliate_id . " from session";
	}
	if ($affiliate_id == 0 && isset($_COOKIE['socialbug_affiliate_id']) && $_COOKIE['socialbug_affiliate_id'] > 0)
	{
		$affiliate_id = $_COOKIE["socialbug_affiliate_id"];
		$brt_debug_message = $brt_debug_message . $affiliate_id . " from cookie 1";
	}
	if ($affiliate_id == 0)
	{
		$affiliate = sb_get_shopping_with();
		if ($affiliate["AffiliateId"] > 0)
		{
			$affiliate_id = $affiliate["AffiliateId"];
			$brt_debug_message = $brt_debug_message . $affiliate_id . " from cookie 2";
		}
	}
	if ($affiliate_id == 0)
	{
		$brt_debug_message = "no affiliate id found";
	}
	brt_debug_init('sb_get_affiliate_id', $brt_debug_message, $affiliate_id);
	return $affiliate_id;
}

function sb_persist_session_with_cookie() {
	if( !session_id() )
	{
		session_start();
	}

	$affiliate = sb_get_shopping_with();
	//if affiliate id is zero then we should see if we have username and or id in a cookie
	//and if so we should try and populate the shopping with cookie
	if ($affiliate["AffiliateId"] == 0 && isset($_COOKIE['sb_cookie_affiliate_username']))
	{
	//call server and try to get affiliate
		$affiliate = sb_set_shopping_with_exec($_COOKIE['sb_cookie_affiliate_username'], false);
	}
	else if ($affiliate["AffiliateId"] == 0 && isset($_COOKIE['socialbug_affiliate_id']))
	{
	//call server and try to get affiliate
		$affiliate = sb_set_shopping_with_exec($_COOKIE['socialbug_affiliate_id'], true);

	}
	if ($affiliate["AffiliateId"] > 0)
	{
	//always make available in session. We do this every time just incase session is disabled because of
	//server caching
		$_SESSION['socialbug_affiliate_id'] = $affiliate["AffiliateId"];
		$_SESSION['socialbug_name'] = $affiliate["Name"];
		$_SESSION['socialbug_city'] = $affiliate["City"];
		$_SESSION['socialbug_email'] = $affiliate["Email"];
		$_SESSION['socialbug_phone'] = $affiliate["PhoneNumber"];
		if(class_exists('WP_Session'))
		{
			$wp_session = WP_Session::get_instance();
			$wp_session['socialbug_affiliate_id'] = $affiliate["AffiliateId"];
			$wp_session['socialbug_name'] = $affiliate["Name"];
			$wp_session['socialbug_city'] = $affiliate["City"];
			$wp_session['socialbug_email'] = $affiliate["Email"];
			$wp_session['socialbug_phone'] = $affiliate["PhoneNumber"];
		}
	}
}

add_action( 'init', 'sb_persist_session_with_cookie' ); // wp or init is needed
function ampp_affiliate_select( $checkout ) {
    $db = new AffiliateUsers\Includes\DB();
    $users = $db->getUsersByArgs(array ( 'per_page' => -1, 'offset' => 0, ));
    $select_options = array( '' => 'Select an affiliate');
    $affiliate_id = sb_get_shopping_with()['AffiliateId'];
    $affiliate_name = sb_get_shopping_with()['Name'];
    $default = '';    
    foreach ($users as $user) {
        $select_options[$user->username] = $user->name;
        if ($affiliate_id == $user->affiliate_id || $affiliate_name == $user->name) {
            $default = $user->name;
        }
    }
    $options = \AffiliateUsers\Includes\PluginOptions::getPluginOptions();
    wp_enqueue_script('autocomplete_js', AFFILIATE_USERS_URL . 'assets/js/jquery.auto-complete.min.js', array('jquery'));
    wp_enqueue_style( 'autocomplete_css', AFFILIATE_USERS_URL . 'assets/css/jquery.auto-complete.css', false );
    wp_enqueue_script('checkout_js', AFFILIATE_USERS_URL . 'assets/js/checkout.js', array('jquery'));
    woocommerce_form_field( 'affiliate_select', array(
        'class'         => array('affiliate_select form-row-wide'),
        'label'         => __($options['affiliate_title']),

        'required'      => (isset($options['choose_affiliate_in_checkout_required']) && ($options['choose_affiliate_in_checkout_required'] == 'on') ? true : false),
        'default' => $default,
        'custom_attributes' => (empty($default) ? array() : array('disabled' => '1')),
        'type'        => 'text',
        ));
}

require_once(AFFILIATE_USERS_DIR . 'includes/pluginOptions.php');
$options = \AffiliateUsers\Includes\PluginOptions::getPluginOptions();
if ($options['choose_affiliate_in_checkout'] == 'on') {
    add_action( 'woocommerce_after_order_notes', 'ampp_affiliate_select' );
}

function ampp_afiiliate_field_process() {
    if (class_exists('WP_Session')) {
        $wp_session = WP_Session::get_instance();
    } else {
        $wp_session = array();
    }
    // Check if set, if its not set add an error.
    $options = \AffiliateUsers\Includes\PluginOptions::getPluginOptions();
    if (isset($options['choose_affiliate_in_checkout']) && $options['choose_affiliate_in_checkout'] == 'on' && isset($options['choose_affiliate_in_checkout_required']) && $options['choose_affiliate_in_checkout_required'] == 'on' && !isset($_SESSION['socialbug_name']) && !isset($wp_session['socialbug_name'])){
        wc_add_notice( __( $options['affiliate_title'].' is not selected.' ), 'error' );
    }else{
        return true;
    }
}

add_action('woocommerce_checkout_process', 'ampp_afiiliate_field_process');
add_action('wp_ajax_nopriv_get_affiliates', 'ajax_get_affiliates');
add_action('wp_ajax_get_affiliates', 'ajax_get_affiliates');

function ajax_get_affiliates() {
    global $api_key;
    global $api_url;
    $luv_affiliates = $api_url."GetAffiliateSearch/" . $api_key
    . "?LastOrFirst=" . sanitize_text_field($_POST['name']);
    /*
    $session = curl_init($luv_affiliates);
    curl_setopt($session, CURLOPT_HEADER, FALSE);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
    $get_affils = curl_exec($session);
    curl_close($session);
    */
    $suggestions = array();
    $result = wp_remote_get($luv_affiliates);

    if ( !is_wp_error( $result ) ) {
        $get_affils = $result['body'];
        $affiliates = json_decode($get_affils);

        $count = 0;
        foreach ($affiliates as $affiliate) {
            $suggestions[] = array('username' => $affiliate->Username, 'name' => $affiliate->FirstName.' '.$affiliate->LastName);
            $count++;
            if ($count >= 20) {
                break;
            }
        }
    }
    echo json_encode($suggestions); //encode into JSON format and output
    die(); //stop "0" from being output
}

function sb_load_scripts_in_footer() {
    echo stripslashes(get_option('sb_footer_script'))."\r\n";
}

add_action( 'wp_footer', 'sb_load_scripts_in_footer', 10000000);
?>
