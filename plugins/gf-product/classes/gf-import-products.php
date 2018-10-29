<?php

class Gf_Import_Products
{
    const GF_PRODUCTS_TABLE = 'gf_products';

    public function __construct()
    {

    }

    private function saveItem(WC_Product $product)
    {
        $postId = $product->get_id();
        $supplierId = $product->get_meta('supplier');
        $supplierSku = $product->get_meta('vendor_code');
        $formatedNames = '';
        $categoryIds = $product->get_category_ids();
        if(count($categoryIds) != 0){
            $args = array(
                'taxonomy' => 'product_cat',
                'include' => $categoryIds,
            );
            $categoryNames = get_terms($args);
            foreach ($categoryNames as $name){
                $nameArray[] = $name->name;
            }
            $formatedNames = implode(',', $nameArray);
        }
        $categoryIds = implode(',', $product->get_category_ids());

        $name = addslashes($product->get_name());
        $status = 0;
        if ($product->get_status() === 'publish') {
            $status = 1;
        }
        $shortDescription = addslashes($product->get_short_description());
        $description = addslashes($product->get_description());
        $imageId = $product->get_image_id();
//        $regularPrice = (int) $product->get_price();
        $salePrice = 0;
        if ($product->is_type('variable')){
            $regularPrice = $product->get_variation_regular_price();
        }else{
            $regularPrice = $product->get_regular_price();
        }
        if ($product->get_price() !== $regularPrice) {
            $salePrice = $product->get_price();
        }
        $inputPrice = $product->get_meta('input_price');
        $pdv = $product->get_meta('pdv');
        $attributes = serialize([
            $product->get_attribute('Veličina'),
            $product->get_attribute('Boja'),
        ]);
//        $attributes = serialize($product->get_attributes());
        $manufacturer = $product->get_attribute('pa_proizvodjac');
        $sku = $product->get_sku();
        $createdAt = '';
        if ($product->get_date_created()) {
            $createdAt = $product->get_date_created()->date('Y-m-d H:i:s');
        }
        $type = $product->get_type();
        $stockStatus = 0;
        if ($product->get_stock_status() === 'instock') {
            $stockStatus = 1;
        }
        $postPaid = 1;
        $synced = 1;

        global $wpdb;

        $tableName = $wpdb->prefix . self::GF_PRODUCTS_TABLE;
        $sql = "INSERT INTO `{$tableName}`(
                postId,supplierId,supplierSku,categoryIds,categories,productName,status,shortDescription,description,imageId,
                regularPrice,salePrice,inputPrice,pdv,attributes,postPaid,manufacturer,stockStatus,sku,synced, createdAt, type)
            VALUES ('{$postId}', '{$supplierId}', '{$supplierSku}', '{$categoryIds}','{$formatedNames}','{$name}', '{$status}','{$shortDescription}', 
            '{$description}', '{$imageId}', '{$regularPrice}', '{$salePrice}', '{$inputPrice}', '{$pdv}', '{$attributes}', 
            '{$postPaid}', '{$manufacturer}', '{$stockStatus}', '{$sku}', '{$synced}', '{$createdAt}', '{$type}');";
        if (!$wpdb->query($sql)) {
            $error = 'Failed to save itemId: ' . $product->get_id() . ' - ' . $wpdb->last_error;
            NSS_Log::log($error, NSS_Log::LEVEL_ERROR);
        }

        return true;
    }

    public function gf_import_products()
    {
        $perPage = 3000;
        for ($page = 0; $page < 7; $page++) {
            global $wpdb;

            $sql = "SELECT ID FROM wp_posts WHERE post_type = 'product' AND 
              `ID` NOT IN(SELECT postId FROM wp_gf_products) LIMIT {$perPage};";
            $result = $wpdb->get_results($sql);
            if (!empty($result)) {
                foreach ($result as $value) {
                    $this->saveItem(wc_get_product($value->ID));
                }
                echo sprintf('imported %s items.', count($result)) . PHP_EOL;
            }
        }

//            if ($product instanceof WC_Product_Variable) {
//                foreach ($product->get_available_variations() as $variation) {
//                    var_dump($variation);
//                    die();
//                }
//            }

    }

    public function sync_products()
    {
        global $wpdb;
//        $product = wc_get_product(94464);
        $sql = "SELECT * FROM wp_gf_products";
        $results = $wpdb->get_results($sql);

        $delta = [];
        foreach ($results as $gf_product) {

            $product = wc_get_product($gf_product->postId);

            if ($product->get_meta('supplier') !== $gf_product->supplierId) {
                $delta['supplierId'] = $product->get_meta('supplier');
            }
            if ($product->get_meta('vendor_code') !== $gf_product->supplierSku) {
                $delta['supplierSku'] = $product->get_meta('vendor_code');
            }
            if (implode(',', $product->get_category_ids()) !== $gf_product->categoryIds) {
                $delta['categoryIds'] = implode(',', $product->get_category_ids());
            }
            if (addslashes($product->get_name()) !== $gf_product->productName) {
                $delta['name'] = addslashes($product->get_name());
            }
            $status = 0;
            if ($product->get_status() === 'publish') {
                $status = 1;
            }
            if ($status !== $gf_product->status) ;
            {
                $delta['status'] = $status;
            }
            if (addslashes($product->get_short_description()) !== $gf_product->shortDescription) {
                $delta['shortDescription'] = addslashes($product->get_short_description());
            }
            if (addslashes($product->get_description()) !== $gf_product->description) {
                $delta['description'] = addslashes($product->get_description());
            }
            if ($product->get_image_id() !== $gf_product->imageId) {
                $delta['imageId'] = $product->get_image_id();
            }
            if ($product->get_regular_price() !== $gf_product->regularPrice) {
                $delta['regularPrice'] = $product->get_regular_price();
            }
            if ($product->get_sale_price() !== $gf_product->salePrice) {
                $delta['salePrice'] = $product->get_sale_price();
            }
            if ($product->get_meta('input_price') !== $gf_product->inputPrice) {
                $delta['inputPrice'] = $product->get_meta('input_price');
            }
            if ($product->get_meta('pdv') !== $gf_product->pdv) {
                $delta['pdv'] = $product->get_meta('pdv');
            }
            if (serialize($product->get_attributes()) !== $gf_product->attributes) {
                $delta['attributes'] = serialize($product->get_attributes());
            }
            if ($product->get_attribute('pa_proizvodjac') !== $gf_product->manufacturer) {
                $delta['manufacturer'] = $product->get_attribute('pa_proizvodjac');
            }
            if ($product->get_sku() !== $gf_product->sku) {
                $delta['sku'] = $product->get_sku();
            }
            $stockStatus = 0;
            if ($product->get_stock_status() === 'instock') {
                $stockStatus = 1;
            }
            if ($stockStatus !== $gf_product->stockStatus) {
                $delta['stockStatus'] = $stockStatus;
            }

            //TODO sta se radi sa ovim? :)
//            $postPaid
//            $synced
        }

    }

    public function deleteAllFromTable()
    {
        global $wpdb;
        $sql = "TRUNCATE TABLE wp_gf_products;";
        $wpdb->query($sql);
    }

}
