<?php
/**
 * Created by PhpStorm.
 * User: djavolak
 * Date: 9/13/2018
 * Time: 10:42 PM
 */

namespace Nss\Feed;


class ParserFactory
{

    /**
     * @param array $supplierConfig
     * @param \GuzzleHttp\Client $httpClient
     * @param \Redis $redis
     * @return Parser\ParserInterface
     */
    static public function make(array $supplierConfig, \GuzzleHttp\Client $httpClient, \Redis $redis)
    {
        $parserName = 'Nss\\Feed\\Parser\\' . ucfirst($supplierConfig['name']);

        return new $parserName($httpClient, $redis);
    }
}