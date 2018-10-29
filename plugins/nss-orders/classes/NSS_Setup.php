<?php

class NSS_Setup
{
    const BACKORDER_TABLE = 'nss_backorder';
    const BACKORDER_ITEMS_TABLE = 'nss_backorderItems';

    static function nss_install() {
        global $wpdb;
        $db_version = '0.1';
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $tableName = $wpdb->prefix . self::BACKORDER_TABLE;
        $charsetCollate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$tableName} (
            backOrderId mediumint(9) NOT NULL AUTO_INCREMENT,
            createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            supplierId mediumint(9) NOT NULL,
            status tinyint(1) NOT NULL,
            PRIMARY KEY  (backOrderId)
        ) $charsetCollate;";
        dbDelta($sql);

        $tableName = $wpdb->prefix . self::BACKORDER_ITEMS_TABLE;
        $sql = "CREATE TABLE {$tableName} (
            backOrderItemId mediumint(9) NOT NULL AUTO_INCREMENT,
            name VARCHAR (256),
            itemId mediumint(9) NOT NULL,
            qty mediumint(5) NOT NULL,
            price int(7) NOT NULL,
            pdv varchar(8) NOT NULL,
            orderId mediumint(9) NOT NULL,
            backOrderId mediumint(9) NOT NULL,
            variant varchar(64) NOT NULL,
            vendorCode varchar(255) NOT NULL,
            status tinyint(1) NOT NULL,
            PRIMARY KEY (backOrderItemId)
        ) $charsetCollate;";

        dbDelta($sql);
        add_option('db_version', $db_version);
    }

    static function nss_uninstall()
    {
//        if (!current_user_can('activate_plugins')) {
//            return;
//        }

        // Important: Check if the file is the one
        // that was registered during the uninstall hook.
//        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
//            return;

        global $wpdb;
        $tableName = $wpdb->prefix . self::BACKORDER_TABLE;
        $wpdb->query("DROP TABLE {$tableName}");

        $tableName = $wpdb->prefix . self::BACKORDER_ITEMS_TABLE;
        $wpdb->query("DROP TABLE {$tableName}");
    }
}