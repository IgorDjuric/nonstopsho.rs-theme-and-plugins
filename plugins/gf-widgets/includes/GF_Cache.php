<?php

/**
 * Class GF_Cache
 *
 */
class GF_Cache
{
    /**
     * @var Redis
     */
    public $redis;

    /**
     * GF_Redis constructor.
     * @param $redis
     */
    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(REDIS_HOST);
    }


}