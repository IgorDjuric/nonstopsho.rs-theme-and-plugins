<?php

namespace Nss\Feed\Parser;

use Nss\Feed\Product;

class Asport implements ParserInterface
{
    const CACHE_KEY = 'importFeedQueue:asport:';

    const SUPPLIER_ID = 308;

    private $httpClient;

    private $redis;

    private $source = WP_CONTENT_DIR . '/uploads/asport.xml';

    private $errors = [];

    public function __construct(\GuzzleHttp\Client $client, \Redis $redis)
    {
        $this->httpClient = $client;
        $this->redis = $redis;
    }

    function parseSource($product)
    {
//        $name = strtoupper((string) $product->shortdesc) .' '. str_replace('  ', '', trim((string) $product->name));
        $name = strtoupper((string) $product->manufacturer) .' '. str_replace('  ', '', trim((string) $product->name));
        $description = html_entity_decode(trim((string) $product->description));
        $shortdesc = html_entity_decode(trim((string) $product->shortdesc));
        $status = 'draft'; // pending
        if((int) $product->online === 1) {
            $status = 'publish';
        }
        $stock_status = 'instock';
        if($product->unavailable = 0) {
            $stock_status = 'outofstock';
        }
        $sizes = $this->parseSizes($product->sizes);
        $type = 'simple';
        if (count($sizes['boja']) || count($sizes['velicina'])) {
            $type = 'variable';
        }
        $catsData = explode("\n", file_get_contents(__DIR__ . '/../../old.cats.map.csv'));
        $categories = $this->getCategories((int) $product->categoryid, $catsData);
        if (count($categories) === 0) {
            $this->errors[] = sprintf('Item %s has non existent categoryId: %s.', $name, (int) $product->categoryid);
            return false;
        }

        $dto = [
            'sku' => '',
            'postId' => '',
            'supplierSku' => (string) $product->vendorcode,
            'supplierId' => self::SUPPLIER_ID,
            'categoryIds' => implode(',', $categories),
            'name' => $name,
            'status' => $status,
            'shortDescription' => $shortdesc,
            'description' => $description,
            'imageIds' => '',
            'images' => (string) $product->image,
            'regularPrice' => $this->parsePrice($product->baseprice),
            'salePrice' => $this->parsePrice($product->promoprice),
            'inputPrice' => $this->parsePrice($product->inputprice),
            'stockStatus' => $stock_status,
            'pdv' => (int) $product->pdv,
//                'attributes' => '',
            'postPaid' => (int) $product->postpaid,
            'manufacturer' => (string) $product->manufacturer,
            'boja' => implode(',', $sizes['boja']),
            'type' => $type,
            'velicina' => implode(',', $sizes['velicina']),
            'weight' => '',
            'quantity' => ''
        ];

        return new Product($dto);
    }

    function getCategories($id, $cats) {
        $categories = array();
        foreach ($cats as $catDataString) {
            $catData = str_getcsv($catDataString, ",", '"');
            if ($catData[0] === '') {
                continue;
            }
            if (isset($catData[3])) {
                if (in_array($id, explode(',', $catData[3]))) {
                    $cat = get_term_by('name', trim($catData[0]), 'product_cat');
                    $categories[] = $cat->term_id;
                    if (isset($catData[1])) {
                        $name = str_replace(',', '', trim($catData[1]));
                        $cat = get_term_by('name', $name, 'product_cat');
                        $categories[] = $cat->term_id;
                    }
                    if (isset($catData[2]) && $catData[2] != '') {
                        $name = str_replace(',', '', trim($catData[2]));
                        $cat = get_term_by('name', $name, 'product_cat');
                        if (!is_object($cat)) {
                            var_dump(trim($catData[2]));
                            die();
                        }
                        $categories[] = $cat->term_id;
                    }
                }
            } else {
//                var_dump($catData);
//                die();
            }
        }

        return $categories;
    }

    public function processItems()
    {
        global $wpdb;

        $existingItems = [];
        $storedItems = 0;
        $i = 0;
        foreach ($this->getXml()->product as $product) {
            $i++;
            $vendorCode = (string) $product->vendorcode;
            //@TODO add supplierId
            $sql = "SELECT post_id FROM wp_postmeta WHERE meta_key  = 'vendor_code' AND meta_value = '{$vendorCode}'";
            $result = $wpdb->get_results($sql);
            if (!empty($result)) {
                $existingItems[] = $result[0]->post_id;
//                $existingProduct = wc_get_product($id);
//                var_dump($id);
                continue;
            } else {
                $storedItems++;
                $product = $this->parseSource($product);
                if (!$product) {
                    continue;
                }
                $serializedProduct = serialize($product);

                $key = md5($serializedProduct);
                $this->redis->set(self::CACHE_KEY . $key, $serializedProduct);
                $this->redis->sAdd(self::CACHE_KEY . 'index', $key);
            }
        }
        echo '<p>'. count($this->getXml()->product) .' items parsed.</p>';
        echo '<p>'. $storedItems .' items queued.</p>';
        $this->parseErrors();
//        echo '<p>existing products ('. count($existingItems) .'): ' . implode(',', $existingItems) . '</p>';
    }

    private function parseErrors()
    {
        foreach ($this->errors as $error) {
            var_dump($error);
            \NSS_Log::log(self::class .' FEED PARSING ERROR: ' . $error, \NSS_Log::LEVEL_ERROR);
        }
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getXml()
    {
        $xml = $this->redis->get(self::CACHE_KEY . 'xml');
        if ($xml === false || $xml === '') {
            $xml = file_get_contents($this->source);
            $this->redis->set(self::CACHE_KEY . 'xml', $xml);
        }

        return simplexml_load_string($xml, null, LIBXML_NOCDATA);
    }

    private function parseSizes(\SimpleXMLElement $sizes)
    {
        $sizeAttributes = $sizes->attributes();
        $gtype = (int) $sizeAttributes[0];
        if (!$gtype) {
            throw new \Exception('Invalid size type provided.');
        }

        $returnSizes = [
            'velicina' => [],
            'boja' => []
        ];
        foreach ($sizes->children() as $size) {
            $sizeStatus = $size->attributes();
            $status = (int) $sizeStatus[0];
            // @TODO maybe parse all, but place those variations offline
            if ($status) {
                if ($gtype === 1) {
                    $returnSizes['velicina'][] = (string) $size;
                } else {
                    $returnSizes['boja'][] = (string) $size;
                }
            }
        }

        return $returnSizes;
    }

    private function parsePrice($price)
    {
        $parsedPrice = (int) $price;

        return $parsedPrice;
    }
}