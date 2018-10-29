<?php
/**
 * Nss Feed Import
 *
 * @package     PluginPackage
 * @author      Green Friends
 * @copyright   2018 Green Friends
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Nss Feed Import
 * Plugin URI:
 * Description: auto feed import
 * Version:     1.0.0
 * Author:      Green Friends
 * Author URI:
 * Text Domain: gf-auto-feed-import
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
require ('classes/Parser/ParserInterface.php');
require ('classes/Parser/Vitapur.php');
require ('classes/Parser/Asport.php');
require ('classes/Parser/Nss.php');
require ('classes/ParserFactory.php');
require ('classes/Product.php');
require ('classes/Importer.php');
//require ('classes/NSS_Log.php');

ini_set('max_execution_time', 1200);
ini_set('display_errors', 0);
error_reporting(E_ALL);


//load_plugin_textdomain('gf-automatski-cenovnici', '', plugins_url() . '/gf-automatski-cenovnici/languages');

add_action('admin_menu', 'gf_auto_feed_import_options_create_menu');
function gf_auto_feed_import_options_create_menu() {
    //create new top-level menu
    add_menu_page('Auto feed import', 'Auto feed import', 'administrator', 'gf_feed_import', 'gf_feed', null, 99);
}

function gf_feed() {
    global $wpdb;

    $route = isset($_GET['tab']) ? $_GET['tab'] : '';
    $supplierId = $_GET['supplierId'];
    $supplierId = 666;

    set_time_limit(0);
    ini_set('max_execution_time', 60 * 60 * 6); // 6 hrs

    switch ($route) {
        case 'parseFeed':
            $supplierId = 666;
            gf_start_parsing($supplierId);


            break;

        case 'resetQueue':
            $supplierId = 666;
            gf_reset_queue($wpdb, $supplierId);


            break;

        case 'importItems':
            $supplierId = 666;
            $counts = gf_start_import($wpdb, $supplierId);
            $msg = '<p>updated total of items: ' . $counts['updated'] . '</p>';
            $msg .= '<p>created total of items: ' . $counts['created'] . '</p>';
            $msg .= '<p>from a total of items: ' . $counts['total'] . '</p>';
            NSS_Log::log($msg, NSS_Log::LEVEL_DEBUG);

            break;

        default:

            break;

    }
    renderActions();
}

function renderActions() {
    echo '<a href="admin.php?page=gf_feed_import&tab=parseFeed">Parse feed</a><br />';
    echo '<a href="admin.php?page=gf_feed_import&tab=importItems">Import items</a><br />>';
    echo '<a href="admin.php?page=gf_feed_import&tab=resetQueue">Reset queue</a><br />';
    echo '<a id="import" href="#">TEST</a>';
    ?>
    <script>
        var running = 0;
        jQuery(document).ready(function() {
            jQuery('#import').click(function() {
                running++;
                startImport(running);
            });
        });
        function startImport(page) {
            jQuery.ajax({
                type: "POST",
                url: '/gf-ajax/?import=true',
                data:{'page': page},
                minLength: 0,
                success: function(response){
                    if (response) {
                        page++;
                        startImport(page);
                    }
                }
            });
        }
    </script>

<?php
}

function gf_start_import($wpdb, $supplierId, $offset = 0, $limit = 100) {
    $httpClient = new \GuzzleHttp\Client();
    $redis = new Redis();
    $redis->connect(REDIS_HOST);
    $limit = 500;

    $key = 'importFeedQueue:' . SUPPLIERS[$supplierId]['name'] .':';
    $importer = new Nss\Feed\Importer($redis, $wpdb, $httpClient, $key);
    return $importer->importItems($offset, $limit);
}

function gf_start_parsing($supplierId) {
    $httpClient = new \GuzzleHttp\Client(['timeout' => 0]);
    $redis = new Redis();
    $redis->connect(REDIS_HOST);

    $parser = Nss\Feed\ParserFactory::make(SUPPLIERS[$supplierId], $httpClient, $redis);
    $parser->processItems();
}

function gf_reset_queue($wpdb, $supplierId) {
    $httpClient = new \GuzzleHttp\Client();
    $redis = new Redis();
    $redis->connect(REDIS_HOST);
    $limit = 500;

    $key = 'importFeedQueue:' . SUPPLIERS[$supplierId]['name'] .':';
    $importer = new Nss\Feed\Importer($redis, $wpdb, $httpClient, $key);
    $importer->resetQueue();
}


