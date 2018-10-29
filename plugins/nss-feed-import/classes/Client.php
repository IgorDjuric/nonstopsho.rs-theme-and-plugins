<?php

namespace Nss\Feed;

class Client
{
    private $httpClient;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}