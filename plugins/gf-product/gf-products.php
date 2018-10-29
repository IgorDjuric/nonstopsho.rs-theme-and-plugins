<?php
/**
 * Gf product
 *
 * @package     PluginPackage
 * @author      Green Friends
 * @copyright   2016 Your Name or Company Name
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: GF Products
 * Plugin URI:  https://example.com/plugin-name
 * Description: Custom table for wc products
 * Version:     1.0.0
 * Author:      Green Friends
 * Author URI:  https://example.com
 * Text Domain: gf-products
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
defined('ABSPATH') || exit();
load_plugin_textdomain('gf-products', '', plugins_url() . '/gf-products/languages');

require('classes/gf-products-setup.php');
require('classes/gf-import-products.php');

register_activation_hook(__FILE__, ['GF_Products_Setup', 'gf_products_install']);
register_deactivation_hook(__FILE__, ['GF_Products_Setup', 'gf_products_uninstall']);

add_action('admin_menu', 'gf_products_options_create_menu');
function gf_products_options_create_menu()
{
    //create new top-level menu
    add_menu_page('Gf Products', 'Product sync', 'administrator', 'gf_products_options', 'gf_products_options_page', null, 99);
}

function gf_products_options_page()
{
    global $wpdb;

    if (isset($_POST['importProducts'])) {
        $import = new Gf_Import_Products();
        $import->gf_import_products();
        echo 'import complete';
    }
    if (isset($_POST['syncProducts'])) {
        $sync = new Gf_Import_Products();
        $sync->sync_products();
    }
    if (isset($_POST['dropTable'])) {
        $drop = new Gf_Import_Products();
        $drop->deleteAllFromTable();
    }

    $sql = "SELECT ID FROM wp_posts WHERE post_type = 'product' AND 
              `ID` NOT IN(SELECT postId FROM wp_gf_products);";
    $importCount = $wpdb->query($sql);

    $totalCount = $wpdb->query("SELECT postId FROM wp_gf_products");
    $syncedCount = $wpdb->query("SELECT postId FROM wp_gf_products WHERE synced = 1");
    $notSyncedCount = $wpdb->query("SELECT postId FROM wp_gf_products WHERE synced = 0");
    ?>
    <form action="" method="post">
        <input type="submit" value="Import products" class="primary" name="importProducts"> <span><?=$importCount?> items to import</span>
    </form>
    <form action="" method="post">
        <input type="submit" value="Sync products" class="primary" name="syncProducts">
        <span><?=$syncedCount?> synced items</span>
        <span><?=$notSyncedCount?> NOT synced items</span>
        <span><?=$totalCount?> total items</span>
    </form>
    <form action="" method="post">
        <input type="submit" value="Delete all data from table" class="primary" name="dropTable">
    </form>
    <?php
}