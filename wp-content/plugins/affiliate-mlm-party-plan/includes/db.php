<?php

namespace AffiliateUsers\Includes;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class DB
{
    const AFFILIATE_USERS_TABLE = 'socialbug_affiliates';

    protected $table;

    public function __construct()
    {
        global $wpdb;

        $this->table = $wpdb->prefix . self::AFFILIATE_USERS_TABLE;
    }

    /*
     * Create table
     */
    public function createTable()
    {
        global $wpdb;

        if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table}'") == $this->table) {
            $sql = "DROP TABLE `{$this->table}`;";
            dbDelta($sql);
        }

        $sql = "CREATE TABLE `{$this->table}` (
            `affiliate_id` int(11) unsigned NOT NULL,
            `username` varchar(100) NOT NULL default '',
            `name` varchar(100) NOT NULL default '',
            `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
            `updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY (affiliate_id),
            KEY username (username)
        );";

        dbDelta($sql);
    }

    /*
     * Insert new client
     */
    public function addUser($data)
    {
        global $wpdb;

        $wpdb->insert(
            $this->table,
            array_merge($data, array(
                    'created_at' => current_time('mysql', true),
                    'updated_at' => current_time('mysql', true),
                )
            )
        );
    }

    /*
     * Get client by field
     */
    public function getUserByField($field, $value)
    {
        global $wpdb;

        $field = esc_sql($field);
        $value = esc_sql($value);
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE {$field} = %s", $value));

        return $user;
    }

    /*
     * Get client by arguments
     */
    public function getUsersByArgs($args = null)
    {
        global $wpdb;

        $defaults = array(
            'per_page' => 20,
            'orderby' => 'created_at',
            'order' => 'ASC',
            'offset' => 0,
        );

        $args = wp_parse_args($args, $defaults);
        foreach ($args as &$arg) {        	
            $arg = esc_sql($arg);
        }

        $where = array();
        if (isset($args['s'])) {
            $where[] = "username LIKE '%{$args['s']}%'";
            $where[] = "name LIKE '%{$args['s']}%'";
        }

        $where_str = $where ? ' WHERE ' . implode(' OR ', $where) : '';

        if (isset($args['bulk'])) {
            $ids_str = implode(',', array_map('intval', $args['bulk']));
            $where_str = " WHERE affiliate_id IN ({$ids_str})";
        }

        $limit_str = (isset($args['per_page']) && ($args['per_page'] > 0))
            ? " LIMIT {$args['offset']}, {$args['per_page']}"
            : '';

        $sql = "SELECT * FROM {$this->table}{$where_str} ORDER BY {$args['orderby']} {$args['order']}{$limit_str}";
        $users = $wpdb->get_results($sql);

        return $users;
    }

    public function getCount()
    {
        global $wpdb;

        return $wpdb->get_var( "SELECT COUNT(affiliate_id) FROM {$this->table}" );
    }

    public function bulkRemoveUsers($ids)
    {
        global $wpdb;

        $q = $wpdb->prepare( "DELETE FROM {$this->table} WHERE affiliate_id IN (".implode( ', ', array_fill(0, count( $ids ), '%d') ).")", $ids );
        return $wpdb->query( $q );
    }
}




