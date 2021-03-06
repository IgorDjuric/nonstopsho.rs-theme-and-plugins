<?php

namespace Gf\Search\Factory;

use \GF\Search\Factory\ElasticClientFactory as ElasticFactory;

/**
 * Class TermSetupFactory
 * @package Gf\Search\Factory
 */
class TermSetupFactory
{
    /**
     * @var ElasticClientFactory
     */
    private $elasticClientFactory;

    /**
     * ProductSetupFactory constructor.
     */
    public function __construct(ElasticFactory $elasticClientFactory)
    {
        $this->elasticClientFactory = $elasticClientFactory;
    }

    /**
     * @return \GF\Search\Elastica\Setup
     */
    public function make()
    {
        return new \GF\Search\Elastica\Setup(
            $this->elasticClientFactory->make(),
            new \GF\Search\Elastica\Config\Term()
        );
    }
}