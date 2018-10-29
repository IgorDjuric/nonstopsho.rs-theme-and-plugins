<?php

function set_html_content_type() { return 'text/html'; }

class NSS_Backorder
{
    /**
     * @var wpdb $wpdb
     */
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getBackOrders($id = null)
    {
        $tableA = $this->db->prefix . NSS_Setup::BACKORDER_TABLE;
        $tableB = $this->db->prefix . NSS_Setup::BACKORDER_ITEMS_TABLE;

        if ($id) {
            $sql = "SELECT *, a.status as orderStatus, b.status as itemStatus FROM {$tableA} a JOIN {$tableB} b USING (backOrderId) 
            WHERE a.backOrderId = {$id} ORDER BY name";
        } else {
            $sql = "SELECT * FROM {$tableA} ORDER BY createdAt DESC";
        }

        return $this->db->get_results($sql);
    }

    public function getNewBackOrders($id = null)
    {
        $tableA = $this->db->prefix . NSS_Setup::BACKORDER_TABLE;
        $tableB = $this->db->prefix . NSS_Setup::BACKORDER_ITEMS_TABLE;

        if ($id) {
            $sql = "SELECT *, a.status as orderStatus, b.status as itemStatus FROM {$tableA} a JOIN {$tableB} b USING (backOrderId) 
          WHERE a.backOrderId = {$id}";
        } else {
            $sql = "SELECT * FROM {$tableA} WHERE status = 1";
        }

        return $this->db->get_results($sql);
    }

    /**
     * Should be fired via cron operation
     *
     * @throws Exception
     */
    public function createBackOrders()
    {
        $arg = array('orderby' => 'date', 'status' => ['u-pripremiplaceno', 'u-pripremi'], 'posts_per_page' => '100');
        $orders = WC_get_orders($arg);
        $data = [];
        //sort by vendors
        foreach ($orders as $order) {
            /** @var WC_Order_Item_Product $item */
            foreach ($order->get_items() as $item) {
                $product = $item->get_product();
                if (!$product) {
                    throw new Exception('Product not found: ' . $item->get_product_id());
                }
                $itemData = [
                    'productId' => $item->get_product_id(),
                    'vendorCode' => $item->get_meta('vendor_code'),
                    'qty' => $item->get_quantity(),
                    'name' => $item->get_name(),
                    'price' => $product->get_price(),
                    'pdv' => $product->get_meta('pdv'),
                    'orderId' => $order->get_id(),
                    'variant' => ''
                ];
                if ($product instanceOf WC_Product_Variation && !empty($product->get_variation_attributes())) {
                    $itemData['variant'] = array_values($product->get_variation_attributes())[0];
                }
                $supplier_id = $product->get_meta('supplier');
                if ($supplier_id === '') {
                    $supplier_id = get_post_meta($item->get_product_id())['supplier'][0];
                }

                $data[$supplier_id][] = $itemData;
            }
            $order->update_status('naruceno');
        }
        $tableA = $this->db->prefix . NSS_Setup::BACKORDER_TABLE;
        $tableB = $this->db->prefix . NSS_Setup::BACKORDER_ITEMS_TABLE;

        try {
            foreach ($data as $suppId => $items) {
                $backorderId_sql = "SELECT backOrderId FROM {$tableA} WHERE supplierId = {$suppId} AND status = 1";
                $result = $this->db->get_results($backorderId_sql);
                if (!empty($result)) {
                    //existing backorder
                    $backOrderId = $result[0]->backOrderId;
                    // loop tru items and add new ones
                    foreach ($items as $item) {
                        $item_sql = "SELECT * FROM {$tableB} WHERE itemId = {$item['productId']} AND backOrderId = {$backOrderId}";
                        $itemData = $this->db->get_results($item_sql);
                        if (empty($itemData)) {
                            // new item
                            $this->db->insert(
                                $this->db->prefix . NSS_Setup::BACKORDER_ITEMS_TABLE,
                                array(
                                    'name' => $item['name'],
                                    'itemId' => $item['productId'],
                                    'qty' => $item['qty'],
                                    'price' => $item['price'],
                                    'pdv' => $item['pdv'],
                                    'orderId' => $item['orderId'],
                                    'backOrderId' => $backOrderId,
                                    'variant' => $item['variant'],
                                )
                            );
                        } else {
                            // update item
                            $sql_variant = "SELECT variant FROM {$tableB} WHERE itemId LIKE {$item['productId']}";
                            $variant_result = $this->db->get_results($sql_variant)[0]->variant;
                            $sql_qty = "SELECT qty FROM {$tableB} WHERE itemId LIKE {$item['productId']}";
                            $old_qty = $this->db->get_results($sql_qty)[0]->qty;
                            $new_qty = (int) $old_qty + (int) $item['qty'];
                            //if same variant update qty only
                            $update_sql = "UPDATE {$tableB} SET qty = {$new_qty} WHERE itemId LIKE {$item['productId']} AND backOrderId = {$backOrderId}";
                            if ($variant_result == $item['variant']) {
                                $this->db->query($update_sql);
                            } else {
                                $this->db->insert(
                                    $this->db->prefix . NSS_Setup::BACKORDER_ITEMS_TABLE,
                                    array(
                                        'name' => $item['name'],
                                        'itemId' => $item['productId'],
                                        'qty' => $item['qty'],
                                        'price' => $item['price'],
                                        'pdv' => $item['pdv'],
                                        'orderId' => $item['orderId'],
                                        'backOrderId' => $backOrderId,
                                        'variant' => $item['variant'],
                                    )
                                );
                            }
                        }
                    }
                } else {
                    $this->createBackOrder($suppId, $items);
                }
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public function processItems($backOrderId, $data)
    {
        $itemsTable = $this->db->prefix . NSS_Setup::BACKORDER_ITEMS_TABLE;

        // collect touched orders to check if its status needs an update
        $orders = [];
        if ($data) {
            foreach ($data as $orderId => $itemIds) {
                $orders[] = $orderId;
                $items = implode(',', array_keys($itemIds));
                $this->db->show_errors(true);
                $sql = "UPDATE {$itemsTable} SET status = 1 WHERE orderId = {$orderId} AND backOrderId = {$backOrderId} AND itemId IN ({$items})";
                $update = $this->db->query($sql);
                if (!$update) {
                    var_dump($this->db->last_error);
                    var_dump($this->db->print_error());
                    die();
                }
            }
        }

        //find items, and reduce local quantity if applicable
        $sql = "SELECT * FROM {$itemsTable} WHERE backOrderId = {$backOrderId} AND status = 0";
        $items = $this->db->get_results($sql);
        foreach ($items as $item) {
            $product = wc_get_product($item->itemId);
            if ((int)$product->get_meta('quantity') > 0) {
                $qty = (int)$product->get_meta('quantity') - (int)$item->qty;
                update_post_meta($item->itemId, 'quantity', $qty);
                $sql = "UPDATE {$itemsTable} SET status = 1 WHERE orderId = {$item->orderId} AND backOrderId = {$backOrderId} AND itemId = {$item->itemId}";
                $this->db->query($sql);
            }
            $orders[] = $item->orderId;
        }

        //update statuses for complete shipments for orders
        foreach ($orders as $orderId) {
            $order = wc_get_order($orderId);
            $sql = "SELECT COUNT(*) as waitingItemsCount FROM wp_nss_backorderItems WHERE orderId = {$orderId} AND status <> 1;";
            if ((int)$this->db->get_results($sql)[0]->waitingItemsCount === 0) {
                if ($order->get_status() !== 'spz-slanje') {
                    $order->update_status('spz-slanje');
                } else if ($order->get_status() !== 'spz-pakovanje' && $order->get_status() !== 'spz-slanje') {
                    $order->update_status('spz-pakovanje');
                }
            }
        }

        $sql = "SELECT COUNT(*) as count FROM {$itemsTable} WHERE backOrderId = {$backOrderId} and status = 0";
        $itemCount = (int)$this->db->get_results($sql)[0]->count;
        $status = 3;
        //all items processed, update back order status
        if ($itemCount === 0) {
            $status = 4;
        }
        $update = $this->db->update(
            $this->db->prefix . NSS_Setup::BACKORDER_TABLE,
            ['status' => $status],
            ['backOrderId' => $backOrderId]
        );
        if (!$update && $this->db->last_error != '') {
            throw new Exception($this->db->last_error);
        }

        return true;
    }

    public function sendBackOrderEmail($supplierId, $backorders)
    {
        $supplier = get_user_by('id', $supplierId);
//        $supplier = get_users(
//            array(
//                'meta_key' => 'vendorid',
//                'meta_value' => $supplierId,
//                'number' => 1,
//                'count_total' => false
//            )
//        )[0];
//        $supplier = get_userdata($supplierId);
        if (!$supplier) {
            throw new Exception('Supplier not found: ' . $supplierId);
        }
        $text = '';
        $from = 'prodaja@nonstopshop.rs';
        include(__DIR__ . '/../html/backOrderEmail.php');

//        $to[] = 'djavolak@mail.ru';
        $to[] = $supplier->user_email;
        $to[] = 'nemanja.mitrovic@nonstopshop.rs';
        // fix from headers
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            "From: NonStopShop <'{$from}'>",
            "Reply-to: NonStopShop <'{$from}'>"
        ];

//        add_filter('wp_mail_content_type', 'set_html_content_type');
        wp_mail($to, 'NonStopShop.rs - Narudžbenica', $text, $headers);
        remove_filter('wp_mail_content_type', 'set_html_content_type');

        $table = $this->db->prefix . NSS_Setup::BACKORDER_TABLE;
        $update_sql = "UPDATE {$table} SET status = 2 WHERE backOrderId = {$_GET['id']}";
        $this->db->query($update_sql);
    }

    protected function createBackOrder($supplierId, $items, $status = 1)
    {
        $this->db->insert(
            $this->db->prefix . NSS_Setup::BACKORDER_TABLE,
            array(
                'supplierId' => $supplierId,
                'status' => $status,
            )
        );
        $backOrderId = $this->db->insert_id;
        foreach ($items as $item) {
            $this->db->insert(
                $this->db->prefix . NSS_Setup::BACKORDER_ITEMS_TABLE,
                array(
                    'name' => $item['name'],
                    'itemId' => $item['productId'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'pdv' => $item['pdv'],
                    'orderId' => $item['orderId'],
                    'backOrderId' => $backOrderId,
                    'variant' => $item['variant'],
                )
            );
        }
    }
}