<?php

namespace Ingelby\Bigcommerce\Api;

use ingelby\toolbox\services\inguzzle\InguzzleHandler;

abstract class AbstractHandler extends InguzzleHandler
{

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var int
     */
    protected $cacheTimeout = 15 * 60;

    /**
     * @param int $cacheTimeout
     */
    public function setCacheTimeout(int $cacheTimeout)
    {
        $this->cacheTimeout = $cacheTimeout;
    }
}
