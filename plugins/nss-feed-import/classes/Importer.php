<?php

namespace Nss\Feed;

/**
 * fix when used via ajax from frontend
 */
if (!function_exists('media_sideload_image')) {
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}

class Importer
{
    private $redis;

    /**
     * @var \wpdb $wpdb
     */
    private $db;

    private $httpClient;

    private $baseKey;

    private $debug;

    /**
     * Importer constructor.
     * @param \Redis $redis
     * @param \wpdb $wpdb
     * @param \GuzzleHttp\Client $httpClient
     * @param $key
     * @param $debug
     */
    public function __construct(\Redis $redis, \wpdb $wpdb, \GuzzleHttp\Client $httpClient, $key, $debug = true)
    {
        $this->redis = $redis;
        $this->db = $wpdb;
        $this->httpClient = $httpClient;
        $this->baseKey = $key;
        $this->debug = $debug;
    }

    public function getCount()
    {
        $keys = $this->redis->sMembers($this->baseKey . 'index');

        return count($keys);
    }

    public function resetQueue()
    {
        $keys = $this->redis->sMembers($this->baseKey . 'index');
        foreach ($keys as $key) {
            $this->redis->sRemove($this->baseKey . 'index', $key);
        }
        echo 'queue clean.';
    }

    public function importItems($offset = 0, $limit = 50)
    {
        $keys = $this->redis->sMembers($this->baseKey . 'index');
        $total = count($keys);
        $keys = array_slice($keys, $offset, $limit, true);

        // remove items from queue, to prevent duplicate items
        foreach ($keys as $key) {
            $this->redis->sRemove($this->baseKey . 'index', $key);
        }
        $importCount = 0;
        $keyRemoveCount = 0;
        $created = [];
        $updated = [];
        foreach ($keys as $key) {
            try {
                /* @var Product $product */
                $product = unserialize($this->redis->get($this->baseKey . $key));

                //nss items
                if ($product->getSku() != '') {
                    $existing = wc_get_product_id_by_sku($product->getSku());
                    if ($existing) {
                        $wcProduct = wc_get_product($existing);

                        $this->updateWcProduct($wcProduct, $product);
                        $updated[] = $product->getSku();
                        continue;
                    }
                }

                if ($product->getType() === 'simple') {
                    $this->saveSimpleItem(new \WC_Product(), $product);
                    $importCount++;
                } else {
                    //TODO check for variable products where 'boja' and 'velicina' are empty
                    if ($product->getBoja() === '' && $product->getVelicina() === '') {
                        \NSS_Log::log('missing attributes. ' . $product->getSku() . ' - ' . $product->getName());
//                        var_dump('missing attributes', $product->getSku(), $product->getName());
//                        $this->redis->sRemove($this->baseKey . 'index', $key);
//                        $keyRemoveCount++;
                        continue;
                    }
                    $this->saveVariableItem($product);
                    $importCount++;
                }
                $created[] = $product->getSku();
//                $this->redis->sRemove($this->baseKey . 'index', $key);
            } catch (\Exception $e) {
                if (get_class($e) === \WC_Data_Exception::class) {
                    \NSS_Log::log($e->getMessage() . ' - ' . $key);
//                    $this->redis->sRemove($this->baseKey . 'index', $key);
//                    $keyRemoveCount++;
                    continue;
                }
            }
        }

        return [
            'importCount' => $importCount, 'keyRemoveCount' => $keyRemoveCount, 'created' => $created,
            'updated' => $updated, 'total' => $total];
    }

    /**
     * Save product data when item is inserted into database.
     *
     * @TODO sku
     *
     * @param \WC_Product $product
     * @param Product $productData
     *
     * @return \WC_Product
     */
    public function saveSimpleItem(\WC_Product $product, Product $productData)
    {
//        var_dump('simple');
//        var_dump($productData);
        $product->set_name($productData->getName());
        $product->set_status($productData->getStatus());
        if ($productData->getSalePrice() > 0) {
            $product->set_sale_price($productData->getSalePrice());
        }
        if ($productData->getInputPrice() > 0) {
            $product->update_meta_data('input_price', $productData->getInputPrice());
        }
        $product->set_catalog_visibility('visible');
        $product->set_short_description($productData->getShortDescription());
        $product->set_description($productData->getDescription());
        $product->set_stock_status($productData->getStockStatus());
        $product->set_weight($productData->getWeight());
        $product->set_reviews_allowed(1);
        $product->set_regular_price($productData->getRegularPrice());
        $product->update_meta_data('pa_proizvodjac', $productData->getManufacturer());
        $product->update_meta_data('pdv', $productData->getPdv());
        $product->update_meta_data('vendor_code', $productData->getSupplierSku());
        $product->update_meta_data('supplier', $productData->getSupplierId());
        $product->update_meta_data('quantity', $productData->getQuantity());
        $categories = explode(',', $productData->getCategoryIds());
        if (count($categories) === 0) {
            throw new \Exception('no categories ' . $productData->getCategoryIds());
        }
        $product->set_category_ids($categories);
        $product->save();

        $product->set_sku($product->get_id());
        $this->handleImage($productData->getImages(), $product->get_id());

        //nss, can be removed after migration
        if ($productData->getSku() !== '') {
            $product->update_meta_data('createdAt', $productData->getCreatedAt());
            $mysqlTs = date('Y-m-d H:i:s', strtotime($productData->getCreatedAt()));
            wp_update_post(array(
                'ID' => $product->get_id(), // ID of the post to update
                'post_date' => $mysqlTs,
                'post_date_gmt' => get_gmt_from_date($mysqlTs)
            ));
            $product->set_sku($productData->getSku());
        }

        $product->save();

        return $product;
    }

    public function saveVariableItem(Product $productData)
    {
        //set basic data for parent product
        $post = array(
            'post_content' => $productData->getDescription(),
            'post_status' => 'publish',
            'post_title' => $productData->getName(),
            'post_parent' => '',
            'post_type' => 'product'
        );
        $post_id = wp_insert_post($post);
        wp_set_object_terms($post_id, 'variable', 'product_type');
        $product = wc_get_product($post_id);
        $product_id = $product->get_id();
        $product->set_status($productData->getStatus());
        if ($productData->getSalePrice() > 0) {
            $product->set_sale_price($productData->getSalePrice());
        }
        if ($productData->getInputPrice() > 0) {
            $product->update_meta_data('input_price', $productData->getInputPrice());
        }
        $product->set_catalog_visibility('visible');
        $product->set_short_description($productData->getShortDescription());
        $product->set_stock_status($productData->getStockStatus());
        $product->set_weight($productData->getWeight());
        $product->set_reviews_allowed(1);
        $product->set_regular_price($productData->getRegularPrice());
        $product->update_meta_data('pa_proizvodjac', $productData->getManufacturer());
        $product->update_meta_data('pdv', $productData->getPdv());
        $product->update_meta_data('vendor_code', $productData->getSupplierSku());
        $product->update_meta_data('supplier', $productData->getSupplierId());
        $product->update_meta_data('quantity', $productData->getQuantity());
        $categories = explode(',', $productData->getCategoryIds());
        if (count($categories) === 0) {
            throw new \Exception('no categories ' . $productData->getCategoryIds());
        }
        if (count($categories) === 1) {
            $cat = get_term_by('term_id', $categories[0], 'product_cat');
//            var_dump($productData);
            $categories[] = $cat->parent;
            die();
        }
        $product->set_category_ids($categories);
        $product->save();

        $product->set_sku($product->get_id());
        $this->handleImage($productData->getImages(), $product->get_id());

        //nss, can be removed after migration
        if ($productData->getSku() !== '') {
            $product->update_meta_data('createdAt', $productData->getCreatedAt());
            $mysqlTs = date('Y-m-d H:i:s', strtotime($productData->getCreatedAt()));
            wp_update_post(array(
                'ID' => $product->get_id(), // ID of the post to update
                'post_date' => $mysqlTs,
                'post_date_gmt' => get_gmt_from_date($mysqlTs)
            ));
            $product->set_sku($productData->getSku());
        }
        $product->save();

        //detect product attribute and set variation data
        if ($productData->getBoja() !== '') {
            $variation_data['boja'] = explode(',', mb_strtolower($productData->getBoja()));
        }
        if ($productData->getVelicina() !== '') {
            $variation_data['velicina'] = explode(',', mb_strtolower($productData->getVelicina()));
        }
//        var_dump($variation_data);


        // Iterating through the variations attributes and set attribute values
        foreach ($variation_data as $attribute => $attribute_value) {
            $attribute = 'pa_' . $attribute;
            foreach ($attribute_value as $name) {
//                $clean_name = trim($this->fixBadUtf8($name));
                $name = sanitize_title($name);
                wp_set_object_terms($product_id, $name, $attribute, true);
                $attribute_data[sanitize_title($attribute)] = Array(
                    'name' => wc_clean($attribute),
                    'value' => $name,
                    'is_visible' => '1',
                    'is_variation' => '1',
                    'is_taxonomy' => '1'
                );
            }
            update_post_meta($product_id, '_product_attributes', $attribute_data);
        }

        //create variation for each attribute value
        foreach ($variation_data as $attribute => $value) {
            foreach ($value as $name) {
                $name = sanitize_title($name);
                $variation_post = array(
                    'post_title' => $product->get_title(),
                    'post_name' => 'product-' . $product_id . '-variation',
                    'post_status' => 'publish',
                    'post_parent' => $product_id,
                    'post_type' => 'product_variation',
                    'guid' => $product->get_permalink()
                );

                $variation_post_id = wp_insert_post($variation_post);
//                $attribute_term = get_term_by('name', $clean_name, 'pa_' . $attribute);
                update_post_meta($variation_post_id, 'attribute_pa_' . $attribute, $name);
                $variation = wc_get_product($variation_post_id);
                if (empty($variation->get_regular_price())) {
                    $variation->set_regular_price($productData->getRegularPrice());
                }
                if (empty($variation->get_sale_price())) {
                    $variation->set_sale_price($productData->getSalePrice());
                }
                $variation->save();
            }
        }
//        return $variation;
    }

    /**
     * Update product data when item is updated.
     *
     * @param \WC_Product $product
     * @param Product $productData
     * @return \WC_Product
     */
    public function updateWcProduct(\WC_Product $product, Product $productData)
    {
        $product->update_meta_data('supplier', $productData->getSupplierId());
        $product->save();
        return $product;


        $changed = false;
        if ($productData->getDescription() != '' && $productData->getDescription() != $product->get_sale_price()) {
            $product->set_description($productData->getDescription());
            $changed = true;
        }

        if ($productData->getShortDescription() != '' && $productData->getShortDescription() != $product->get_short_description()) {
            $product->set_short_description($productData->getShortDescription());
            $changed = true;
        }

        if ($productData->getSalePrice() > 0 && ($productData->getSalePrice() !== (float)$product->get_sale_price())) {
            $product->set_sale_price($productData->getSalePrice());
            $changed = true;
        }

        if ($productData->getInputPrice() > 0 && ($productData->getInputPrice() !== (float)$product->get_meta('input_price'))) {
            $product->update_meta_data('input_price', $productData->getInputPrice());
            $changed = true;
        }

        if ($productData->getRegularPrice() !== (float)$product->get_regular_price()) {
            $product->set_regular_price($productData->getRegularPrice());
            $changed = true;
        }
        if ($product->get_status() !== $productData->getStatus()) {
            $product->set_status($productData->getStatus());
            $changed = true;
        }
//        if ($product->get_status() === 'pending' && $productData->getStatus() == 1) {
//            $status = 'publish';
//            $product->set_status($status);
//            $changed = true;
//        }

//        if($productData->getManufacturer() != '' || $productData->getManufacturer() !== $product->get_meta('suplier')){
//            $product->update_meta_data('supplier', $productData->getManufacturer());
//            $changed = true;
//        }
        if ($productData->getPdv() > 0 && $productData->getPdv() !== $product->get_meta('_pdv')) {
            $product->update_meta_data('_pdv', $productData->getPdv());
            $changed = true;
        }


        //TODO napraviti bolju proveru za slike
//        $product_thumbnail_id = $product->get_image_id();
//        $product_gallery_ids = $product->get_gallery_image_ids();
//        if($product_thumbnail_id !== ''){
//            $product_gallery_ids[] = $product_thumbnail_id;
//        }
//        var_dump($product_gallery_ids);
//        if (!empty($product_gallery_ids)) {
//            foreach ($product_gallery_ids as $image_id) {
//                wp_delete_attachment($image_id, true);
//                var_dump('obrisano');
//            }
//        }
//
//        $this->handleImage($productData->getImages(), $product->get_id());

//        $product->save();


        if ($changed) {
            $product->save();
        }

        return $product;
    }

    /**
     * Inserts images into media library for given post id.
     *
     * @param $images
     * @param $productId
     * @throws \Exception
     */
    private function handleImage($images, $productId)
    {
        $explodedImages = explode(',', $images);
        if ($images == '' || count($explodedImages) === 0) {
            return false;
        }
        $image_main_url = $explodedImages[0];

        if (strstr($image_main_url, 'ftp')) {
            return $this->handleFtpImage($explodedImages, $productId);
        }

        if (is_object($image_main_url) && get_class($image_main_url) === \WP_Error::class) {
            $msg = 'Failed to fetch image for item: ' . $productId . PHP_EOL;
            $msg .= $image_main_url->get_error_messages();
            $msg .= $image_main_url;
            \NSS_Log::log($msg, \NSS_Log::LEVEL_ERROR);
            return false;
        }

        if (is_object($productId) && get_class($productId) === \WP_Error::class) {
            $msg = 'Failed to fetch image for item: ' . $productId . PHP_EOL;
            $msg .= $productId->get_error_messages();
            $msg .= $image_main_url;
            \NSS_Log::log($msg, \NSS_Log::LEVEL_ERROR);
            return false;
        }

        //Main image
        $image_main_id = \media_sideload_image($image_main_url, $productId, '', 'id');
        if (is_object($image_main_id) && get_class($image_main_id) === \WP_Error::class) {
            $msg = 'Failed to fetch image for item: ' . $productId . PHP_EOL;
            $msg .= print_r($image_main_id->get_error_messages(), true);
            $msg .= $image_main_url;
            \NSS_Log::log($msg, \NSS_Log::LEVEL_ERROR);
            return false;
        }
        if (is_object($image_main_id) && get_class($image_main_id) === \WP_Error::class) {
            throw new \Exception(sprintf('Could not save image for item %s. Url: %s. Error %s .',
                $productId, $image_main_url, $image_main_id));
        }
        \set_post_thumbnail($productId, $image_main_id);
        \update_post_meta($productId, '_thumbnail_id', $image_main_id);

        //Gallery images
        $image_gallery_urls = explode(',', $images);
        $image_gallery_ids = [];
        foreach ($image_gallery_urls as $key => $url) {
            if ($key > 0) {
                // @TODO check image via http ?
                $image = \media_sideload_image($url, $productId, '', 'id');

                if (is_object($image) && get_class($image) === \WP_Error::class) {
                    throw new \Exception(sprintf('Could not save image for item %s. Url: %s. Error %s .',
                        $productId, $url, $image));
                }
                $image_gallery_ids[] = $image;
            }
        }

        \update_post_meta($productId, '_product_image_gallery', implode(',', $image_gallery_ids));
    }

    private function handleFtpImage($images, $productId)
    {
        $galleryIds = [];
        foreach ($images as $key => $imageUrl) {
            $image = $this->fetchFtpImage($imageUrl);
            $file_array = array();
            $file_array['name'] = basename($image);
            $file_array['tmp_name'] = $image;
            $id = media_handle_sideload($file_array, $productId);
            if (is_wp_error($id)) {
                unlink($file_array['tmp_name']);
                throw new \Exception('failed to save image.' . $imageUrl);
            }
            if ($key > 0) {
                $galleryIds[] = $id;
            } else {
                \set_post_thumbnail($productId, $id);
                \update_post_meta($productId, '_thumbnail_id', $id);
            }
        }
        \update_post_meta($productId, '_product_image_gallery', implode(',', $galleryIds));
    }

    private function fetchFtpImage($url)
    {
        $fileName = basename($url);
        $localFile = ABSPATH . 'wp-content/uploads/feed/' . $fileName;
        $urlInfo = parse_url($url);
        $ftpConnection = ftp_connect($urlInfo['host']);
        ftp_login($ftpConnection, 'anonymous', 'anonymous@example.org');
        ftp_pasv($ftpConnection, true);
        if (!ftp_get($ftpConnection, $localFile, $urlInfo['path'], FTP_BINARY)) {
            var_dump(error_get_last());
            throw new \Exception(sprintf('Could not fetch image %s from %s. ', $urlInfo['path'], $urlInfo['host']));
        }
        ftp_close($ftpConnection);

        return $localFile;
    }
}