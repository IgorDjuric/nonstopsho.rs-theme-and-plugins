<?php

namespace Nss\Feed\Parser;

use Nss\Feed\Product;

class Nss implements ParserInterface
{
    const CACHE_KEY = 'importFeedQueue:nss:';

    const SUPPLIER_ID = 666;

    private $httpClient;

    private $redis;

    private $source = WP_CONTENT_DIR . '/uploads/nss.json';
//    private $source = WP_CONTENT_DIR . '/uploads/nssUpdateTest.json';
//    private $source = 'https://nonstopshop.rs/cms/work/customImportCsv.php';

    public function __construct(\GuzzleHttp\Client $client, \Redis $redis)
    {
        $this->httpClient = $client;
        $this->redis = $redis;
    }

    public function processItems()
    {
//        $response = $this->httpClient->get($this->source);
//        $data = json_decode($response->getBody());
        $json = preg_replace('/[[:cntrl:]]/', '', file_get_contents($this->source));
        $data = \GuzzleHttp\json_decode($json);

        $existingItems = [];
        $storedItems = 0;
        foreach ($data as $item) {
            $id = wc_get_product_id_by_sku($item->sku);

//            if ($id) {
                $existingItems[] = $id;
//                continue;  // enable in order to skip existing items
//            } else {
                $storedItems++;
                $product = $this->parseSource($item);
                $serializedProduct = serialize($product);

                $key = $product->getName() . $product->getSupplierSku();
                $this->redis->set(self::CACHE_KEY . $key, $serializedProduct);
                $this->redis->sAdd(self::CACHE_KEY . 'index', $key);
//            }
        }
        echo '<p>'. count($data) .' items parsed.</p>';
        echo '<p>'. $storedItems .' items queued.</p>';
        echo '<p>existing products ('. count($existingItems) .'): ' . implode(',', $existingItems) . '</p>';
    }

    public function parseSource($item)
    {
        global $wpdb;

        $status = 'draft'; // pending
        if ($item->status === '1') {
            $status = 'publish';
        }
        $stock_status = 'instock';
        if ($item->stockStatus != 1){
            $stock_status = 'outofstock';
        }

        $exploded = explode(',', $item->categories);
        $categories_raw = explode('>', end($exploded));
        $category_ids = [];
        $cat1 = get_term_by('name', trim($categories_raw[0]), 'product_cat');
        $category_ids[] = $cat1->term_id;
        if (isset($categories_raw[1])) {
            $cleanName = trim($categories_raw[1]);
            $sql1 = "SELECT * FROM wp_terms JOIN wp_term_taxonomy USING(term_id) WHERE taxonomy = 'product_cat' AND name = '{$cleanName}' 
              AND parent = {$cat1->term_id} GROUP BY term_id;";
            $cat2 = $wpdb->get_results($sql1)[0];
            $category_ids[] = (int) $cat2->term_id;

            if (isset($categories_raw[2])) {
                $cleanName = trim($categories_raw[2]);
                $sql2 = "SELECT * FROM wp_terms JOIN wp_term_taxonomy USING(term_id) WHERE taxonomy = 'product_cat' AND name = '{$cleanName}' 
              AND parent = {$cat2->term_id} GROUP BY term_id;";
                if ($wpdb->get_results($sql2)) {
                    $cat3 = $wpdb->get_results($sql2)[0];
                    $category_ids[] = (int) $cat3->term_id;
                }
            }
        }
        if (count($category_ids) === 0) {
            throw new \Exception('no categories ' . $item->categories);
        }

        $dto = [
            'postId' => $item->sku,
            'supplierSku' => $item->vendorcode,
            'supplierId' => $item->vendorId,
            'categoryIds' => implode(',', $category_ids),
            'name' => $this->fixBadUtf8($item->name),
            'status' => $status,
            'shortDescription' => $item->shortDescription,
            'description' => $item->description,
            'images' => $item->images,
            'regularPrice' => $item->basePrice,
            'salePrice' => $item->salePrice,
            'inputPrice' => $item->inputPrice,
            'stockStatus' => $stock_status,
            'pdv' => $item->pdv,
            'manufacturer' => $item->proizvodjac,
            'weight' => $item->weight,
            'createdAt' => $item->createdAt,
            'quantity' => $item->quantity,
            'sku' => $item->sku,
            'boja' => $item->boja,
            'type' => $item->type,
            'velicina' => $item->velicina
        ];

        return new Product($dto);
    }

    //TODO fix this shit
    public function fixBadUtf8($text)
    {
        return str_replace('\u017e', 'z', $text);
    }

}