<?php

namespace Nss\Feed\Parser;

use Nss\Feed\Product;

class Vitapur implements ParserInterface
{
    const CACHE_KEY = 'importFeedQueue:vitapur:';

    const SUPPLIER_ID = 1;

    private $httpClient;

    private $redis;

    private $source = 'https://www.vitapur.si/media/feed/non-stop-shop-rs.xml';

    public function __construct(\GuzzleHttp\Client $client, \Redis $redis)
    {
        $this->httpClient = $client;
        $this->redis = $redis;
    }

    public function getXml()
    {
        $xml = $this->redis->get(self::CACHE_KEY . 'xml');
        if ($xml === false || $xml === '') {
            $xml = file_get_contents($this->source);
            $this->redis->set(self::CACHE_KEY . 'xml', $xml);
        }

        return simplexml_load_string($xml, null, LIBXML_NOCDATA);
    }
    function processItems()
    {
        global $wpdb;
        $existingItems = [];
        $storedItems = 0;
        $i = 0;
        foreach ($this->getXml() as $product) {
            $i++;
            $vendorCode = self::SUPPLIER_ID;
            //@TODO add supplierId
            $sql = "SELECT post_id FROM wp_postmeta WHERE meta_key  = 'supplier' AND meta_value = '{$vendorCode}'";
            $result = $wpdb->get_results($sql);
            if (!empty($result)) {
                $existingItems[] = $result[0]->post_id;
                continue;
            } else {
                $storedItems++;
                $product = $this->parseSource($product);
                $serializedProduct = serialize($product);

                $key = md5($serializedProduct);
                $this->redis->set(self::CACHE_KEY . $key, $serializedProduct);
                $this->redis->sAdd(self::CACHE_KEY . 'index', $key);
            }
        }
        echo '<p>'. count($this->getXml()->product) .' items parsed.</p>';
        echo '<p>'. $storedItems .' items queued.</p>';
        echo '<p>existing products ('. count($existingItems) .'): ' . implode(',', $existingItems) . '</p>';
    }

    function parseSource($product)
    {
        $category = $product->kategorija;
        $name = trim((string) $product->naziv);
        $description = (string) $product->opis;
        $shortdesc = (string) $product->kratak_opis;
        $status = 'pending'; // pending
        if((int) $product->dostupnost === 1) {
            $status = 'publish';
        }
        // @TODO waiting for vitapur to fix feed, overriden status until then.
        $status = 'publish';
        $stock_status = 'instock';
        if((int)$product->dostupnost === 0) {
            $stock_status = 'outofstock';
        }
        $type = 'simple';
//        $catsData = explode("\n", file_get_contents(__DIR__ . '/../../old.cats.map.csv'));
//        $categories = $this->getCategories((int) $product->categoryid, $catsData);

        $dto = [
            'sku' => (string) $product->sku,
            'postId' => '',
            'supplierSku' => '',
            'supplierId' => self::SUPPLIER_ID,
//            'categoryIds' => implode(',', $categories),
            'name' => $name,
            'status' => $status,
            'shortDescription' => $shortdesc,
            'description' => $description,
            'imageIds' => '',
            'images' => (string) $product->slika,
            'regularPrice' => $this->parsePrice($product->mp_cena), // proveri da li ti ovo treba, asport je imao ruzhan format cene
            'salePrice' => $this->parsePrice($product->akcijska_cena),
            'inputPrice' => $this->parsePrice($product->vp_cena),
            'stockStatus' => $stock_status,
            'pdv' => '20',
//                'attributes' => '',
//            'postPaid' => (int) $product->postpaid,
//            'manufacturer' => (string) $product->manufacturer,
            'boja' => '',
            'type' => $type,
            'velicina' => '',
            'weight' => (string) $product->tezina,
            'quantity' => ''
        ];

        return new Product($dto);
    }
    private function parsePrice($price)
    {
        $parsedPrice = (int) $price;

        return $parsedPrice;
    }

}