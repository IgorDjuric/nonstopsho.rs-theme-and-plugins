<?php

class GF_Products_Setup
{
    const GF_PRODUCTS_TABLE = 'gf_products';

    static function gf_products_install()
    {
        global $wpdb;
        $db_version = '0.1';
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $tableName = $wpdb->prefix . self::GF_PRODUCTS_TABLE;
        $charsetCollate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE `{$tableName}` (
          `postId` int(11) unsigned NOT NULL,
          `supplierId` int(11) unsigned NOT NULL,
          `supplierSku` int(11) unsigned NOT NULL,
          `categoryIds` varchar(128) NOT NULL,
          `categories` varchar(512) NOT NULL,
          `productName` varchar(128) NOT NULL,
          `status` int(1) unsigned NOT NULL,
          `shortDescription` mediumtext NOT NULL,
          `description` longtext NOT NULL,
          `imageId` int(11) unsigned NOT NULL,
          `regularPrice` int(7) unsigned NOT NULL,
          `salePrice` int(7) unsigned DEFAULT NULL,
          `inputPrice` decimal(14,2) unsigned DEFAULT NULL,
          `pdv` int(3) unsigned DEFAULT NULL,
          `attributes` mediumtext,
          `postPaid` int(1) unsigned DEFAULT '1',
          `manufacturer` varchar(250) DEFAULT NULL,
          `stockStatus` int(1) unsigned NOT NULL,
          `sku` varchar(64) DEFAULT NULL,
          `synced` int(1) unsigned NOT NULL DEFAULT '0',
          `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `createdAt` timestamp NOT NULL,
          `type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
          `viewCount` int(6) NOT NULL DEFAULT '0',
          `rating` int(3) NOT NULL DEFAULT '0',
          PRIMARY KEY (`postId`),
          UNIQUE KEY `postId_UNIQUE` (`postId`),
          KEY `status_INDEX` (`status`),
          KEY `stock_INDEX` (`stockStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        dbDelta($sql);
        add_option('db_version', $db_version);
    }

    static function gf_products_uninstall()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . self::GF_PRODUCTS_TABLE;
        $wpdb->query("DROP TABLE {$tableName}");

        $tableName = $wpdb->prefix . self::GF_PRODUCTS_TABLE;
        $wpdb->query("DROP TABLE {$tableName}");
    }

}