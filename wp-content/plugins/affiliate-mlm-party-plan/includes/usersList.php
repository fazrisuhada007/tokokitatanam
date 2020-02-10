<?php

namespace AffiliateUsers\Includes;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

use AffiliateUsers\Includes\DB;

class UsersList extends \WP_List_Table
{
    public function __construct()
    {
        $current_screen = get_current_screen();
        if (!$current_screen) {
            return;
        }

        $this->screen = $current_screen->id . 'users';
        add_filter('manage_' . $this->screen . '_columns', array($this, 'get_columns'));

        parent::__construct(array(
                'singular' => __('Affiliate User'),
                'plural' => __('Affiliate Users'),
                'ajax' => false,
                'screen' => $this->screen,
            )
        );
    }

    public function no_items()
    {
        _e('No Affiliate Users');
    }

    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'affiliate_id' => __('Affiliate Id'),
            'username' => __('Username'),
            'name' => __('Full Name'),
            'created_at' => __('Registered'),
        );

        return $columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'created_at' => array('created_at', true),
            'affiliate_id' => array('affiliate_id', false),
            'username' => array('username', false),
            'name' => array('name', false),
        );

        return $sortable_columns;
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'created_at':
                $date = new \DateTime($item->{$column_name});
                return $date->format('d.m.Y H:i');

            default:
                return $item->{$column_name};
        }
    }

    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="bulk[]" value="%s" />', $item->affiliate_id);
    }

    public function get_bulk_actions()
    {
        $actions = array(
            'bulk-delete' => __('Delete'),
        );

        return $actions;
    }

    public function column_created_at($item)
    {
        $t_time = mysql2date('d.m.Y H:i', $item->created_at, true);
        $m_time = $item->created_at;
        $time = mysql2date('G', $item->created_at) - get_option('gmt_offset') * 3600;
        $time_diff = time() - $time;

        if (($time_diff > 0) && ($time_diff < 24 * 60 * 60)) {
            $h_time = sprintf(__('%s ago'), human_time_diff($time));
        } else {
            $h_time = mysql2date(__( 'd.m.Y'), $m_time);
        }

        return sprintf('%s<br /><abbr title="%s">%s</abbr>', __('Registered'), $t_time, $h_time);
    }

    public function column_username($item)
    {
        $link = admin_url('admin.php');
        $query_vars = array(
            'page' => filter_input(INPUT_GET, 'page'),
            'tab' => filter_input(INPUT_GET, 'tab'),
            'action' => 'bulk-delete',
            'bulk' => array($item->affiliate_id),
        );

        $delete_link = add_query_arg($query_vars, $link);

        $block = '<strong>%s</strong><br />'
            . '<div class="row-actions">'
            . '<span class="delete"><a href="%s">' . __('Delete') . '</a></span>'
            . '</div>';

        return sprintf($block, $item->username, $delete_link);
    }

    public function process_bulk_action()
    {
        $db = new DB();

        $action = $this->current_action();
        switch ($action) {
            case 'bulk-delete':
                if (isset($_GET['bulk'])) {
                    $bulk = array_map( "strip_tags", $_GET['bulk'] );

                    $db->bulkRemoveUsers( $bulk );

                    $messages[] = array(
                        'type' => 'error',
                        'text' => sprintf( _n( 'One user removed', '%d users removed', count( $bulk ) ), count( $bulk ) ),
                    );
                    set_transient( get_current_user_id() . '_affiliate_users_messages', $messages );

                    $sendback = admin_url( 'admin.php' );
                    $query_vars = array(
                        'page' => filter_input( INPUT_GET, 'page' ),
                        'tab' => filter_input( INPUT_GET, 'tab' ),
                    );
                    $sendback = add_query_arg( $query_vars, $sendback );

                    wp_redirect( $sendback );

                    exit;
                }

                break;
        }
    }

    public function prepare_items()
    {
        $db = new DB();

        $this->_column_headers = $this->get_column_info();

        $per_page = $this->get_items_per_page('users_network_per_page', 20);
        $current_page = $this->get_pagenum();
        $total_items = $db->getCount();

        $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page' => $per_page,
            )
        );

        $args = array(
            'per_page' => $per_page,
            'offset' => ($current_page - 1) * $per_page,
        );

        if ($s = filter_input(INPUT_GET, 's')) {
            $args['s'] = sanitize_text_field($s);
        }
        if (($orderby = filter_input(INPUT_GET, 'orderby')) && in_array(strtolower($orderby), array_keys($this->get_sortable_columns()))) {
            $args['orderby'] = sanitize_text_field($orderby);
        }
        if (($order = filter_input(INPUT_GET, 'order')) && in_array(strtolower($order), array('asc', 'desc'))) {
            $args['order'] = sanitize_text_field($order);
        }

        $this->items = $db->getUsersByArgs($args);
    }

    protected function display_tablenav($which)
    {
        if ($which == 'top') {
            $this->add_bulk_notices();

            echo '<form method="get" class="tablenav">';
            echo sprintf('<input type="hidden" name="%s" value="%s" />', 'page', filter_input(INPUT_GET, 'page'));
            echo sprintf('<input type="hidden" name="%s" value="%s" />', 'tab', filter_input(INPUT_GET, 'tab'));
            $this->search_box(__( 'Search Users' ), 'users');
        }

        parent::display_tablenav($which);

        if ($which == 'bottom') {
            echo '</form>';
        }
    }

    protected function add_bulk_notices()
    {
        if ($messages = get_transient(get_current_user_id() . '_affiliate_users_messages')) {
            foreach ($messages as $message) {
                echo sprintf('<div class="%s"><p>%s</p></div>', $message['type'], $message['text']);
            }
            delete_transient(get_current_user_id() . '_affiliate_users_messages');
        }
    }
}
