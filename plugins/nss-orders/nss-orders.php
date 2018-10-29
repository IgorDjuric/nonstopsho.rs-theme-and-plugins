<?php
/**
 *   Plugin Name: Nss automatic order management
 *   Plugin URI:
 *   Description: Automatic order processing
 *   Author: Green friends
 *   Author URI:
 *   Version: 0.3
 */

// Prevent direct access
defined('ABSPATH') || die();

//require 'vendor/autoload.php';
//require ('classes/NSS_Log.php');
require ('classes/NSS_Backorder.php');
require ('classes/NSS_BankReport.php');
require ('classes/NSS_CourierReport.php');
require ('classes/NSS_Setup.php');

register_activation_hook(__FILE__, ['NSS_Setup', 'nss_install']);
register_deactivation_hook(__FILE__, ['NSS_Setup', 'nss_uninstall']);
//register_uninstall_hook(    __FILE__, 'mrp_uninstall' );

add_action('admin_menu', function (){
    add_menu_page('Obrada porudbzenica', 'Obrada porudbzenica', 'edit_pages', 'nss-orders', 'getTabs');
});

add_action('woocommerce_thankyou', 'nss_orderCreated', 111, 1);

function getTabs(){
    global $wpdb;
    include 'html/myHeader.php';

    $route = isset($_GET['tab']) ? $_GET['tab'] : 'bankReportForm';
    switch ($route){
        case 'bankReportForm':
            include 'html/bankReportForm.php';
            break;

        case 'bankReportAction':
            $bankReport = new NSS_BankReport();
            if (isset($_POST['orderId'])) {
                if (isset($_POST['selected'])) {
                    $unresolvedPayments = $bankReport->changeOrdersStatusByIdFromPost();
                } else {
                    $unresolvedPayments = [];
                    echo 'nista nije izabrano.';
                }
            } else {
                $unresolvedPayments = $bankReport->changeOrdersStatusByIdFromBankReport();
            }
            include('html/bankReportList.php');

            break;

        case 'courierReportForm':
            include 'html/courierReportForm.php';
            break;

        case 'courierReportAction':
            $courierReport = new NSS_CourierReport();
            $courierReport->changeOrderStatusByIdFromCourierReport();

            break;

        case 'backOrderCandidates':
            $arg = array('orderby' => 'date', 'status' => ['u-pripremiplaceno', 'u-pripremi']);
            $orders = WC_get_orders($arg);

            include 'html/backOrderCandidates.php';
            break;

        case 'backOrderManualCreate':
            $backorders = new NSS_Backorder($wpdb);
            $backorders->createBackOrders();
            include 'html/backOrderManualCreate.php';
            break;

        case 'backOrderList':
            $backorders = new NSS_Backorder($wpdb);
            $backorders = $backorders->getBackOrders();
            include 'html/backOrderList.php';

            break;

        //@TODO output required html only, avoid wp stuff
        case 'backOrderCopy':
            $backorder = new NSS_Backorder($wpdb);
            $backorders = $backorder->getBackOrders($_GET['id']);
            /* @var WP_User $supplier */
            $supplier = get_user_by('id', $backorders[0]->supplierId);

            $supplier = get_users(
                array(
                    'meta_key' => 'vendorid',
                    'meta_value' => $backorders[0]->supplierId,
                    'number' => 1,
                    'count_total' => false
                )
            )[0];

            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment;filename="'.$supplier->display_name.' - '.$_GET['id'].'-'.date('dmy').'.html"');

//            echo '<html><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /></head>'.$bodyHead.$body.'</body>';
            include 'html/backOrderProcess.php';

            break;

        case 'backOrderEmail':
            $backorder = new NSS_Backorder($wpdb);
            $backorders = $backorder->getBackOrders($_GET['id']);
            $supplierId = $backorders[0]->supplierId;
            $backorder->sendBackOrderEmail($supplierId, $backorders);

            echo 'mail sent';
            break;

        case 'backOrderProcess':
            $backorders = new NSS_Backorder($wpdb);
            if (isset($_POST['submit'])) {
                $backorders->processItems($_GET['id'], $_POST['itemId']);
            }
            $backorders = $backorders->getBackOrders($_GET['id']);

            include 'html/backOrderProcess.php';

            break;

        case 'itemExport':
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', '300');
        $csv = '';
        for ($i=1; $i<7; $i++) {
            $args     = array(
                'post_type' => 'product',
//                'category' => 34,
                'posts_per_page' => 2000,
                'page' => $i,
                'status' => 'publish'
            );
            $products = wc_get_products($args);

            /* @var $product WC_Product_Simple|WC_Product_Variable */
            foreach($products as $product) {
                if($product->get_meta('pdv') >= 10) {
                    $taxcalc = (int) ('1' . $product->get_meta('pdv'));
                } else {
                    $taxcalc = (int) ('10' . (int) $product->get_meta('pdv'));
                }

                $csv .= @iconv('utf-8','windows-1250',  $product->get_sku()."\t".trim(mb_strtoupper($product->get_name(), 'UTF-8'))."\t".
                        str_replace('.', ',', $product->get_meta('pdv'))."\t".str_replace('.', ',', round($product->get_price() * 100 / (double) $taxcalc, 2))."\t".
                        str_replace('.', ',', round($product->get_price(), 2)))."\r\n";

                if (get_class($product) === WC_Product_Variable::class) {
                    $passedIds = [];
                    foreach ($product->get_available_variations() as $variations) {
                        foreach ($variations['attributes'] as $variation) {
                            $itemIdSize = $product->get_sku() . $variation;
                            if (!in_array($itemIdSize, $passedIds)) {
                                $passedIds[] = $itemIdSize;
                                $csv .= iconv('utf-8','windows-1250',  $itemIdSize."\t".
                                trim(mb_strtoupper($product->get_name() . ' ' . $variation, 'UTF-8'))."\t".
                                str_replace('.',',',$product->get_meta('pdv'))."\t".str_replace('.', ',', round($product->get_price() * 100 / (double) $taxcalc, 2))."\t".
                                str_replace('.', ',', round($product->get_price(), 2)))."\r\n";
//                                var_dump($product->get_sku() . $variation);
                            }
                        }
                    }
                }
            }
        }

//            header("Cache-Control: public");
//            header("Content-Description: File Transfer");
//            header('Content-type: text/plain');
//            header("Content-Disposition: attachment; filename=".date('d-m-Y H:i:s').'.txt');
//            header('Content-Transfer-Encoding: binary');

            echo $csv;
            die();

            break;

        default:
            include 'html/bankReportForm.php';
            break;
    }
}

/**
 * Set appropriate statuses when order is created.
 *
 * @param $orderId
 */
function nss_orderCreated($orderId) {
    $order = wc_get_order($orderId);
    if ($order->get_status() === 'on-hold' && $order->get_payment_method() === 'bacs') {
        $order->update_status('cekaseuplata');
    }
    if ($order->get_status() === 'processing' && $order->get_payment_method() === 'cod') {
        $order->update_status('u-pripremi');
    }
    //@todo test
    if ($order->is_paid()) {
        $order->update_status('u-pripremiplaceno');
    }
}

add_filter('woocommerce_order_number', 'nss_woocommerce_order_number', 1, 2);
/**
 * Add Prefix to WooCommerce Order Number
 */
function nss_woocommerce_order_number($oldnumber, WC_Order $order) {
    $dateCreated = date('dmY', strtotime($order->get_date_created()));
    return $dateCreated .'-'. $order->get_id();

}

//add_filter( 'wc_order_is_editable', 'wc_make_processing_orders_editable', 10, 2 );
//function wc_make_processing_orders_editable( $is_editable, $order ) {
//    if ( $order->get_status() == 'processing' ) {
//        $is_editable = true;
//    }
//
//    return $is_editable;
//}