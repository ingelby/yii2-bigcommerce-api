<?php

namespace Ingelby\Bigcommerce\Api;

use Ingelby\Bigcommerce\Exceptions\BigCommerceClientException;
use Ingelby\Bigcommerce\Exceptions\BigCommerceConfigException;
use ingelby\toolbox\services\inguzzle\exceptions\InguzzleClientException;
use ingelby\toolbox\services\inguzzle\InguzzleHandler;

abstract class AbstractHandler extends InguzzleHandler
{

    protected array $defaultClientConfig = [

    ];

    /**
     * AbstractHandler constructor.
     *
     * @param string        $baseUrl
     * @param callable|null $clientErrorResponseCallback
     * @param callable|null $serverErrorResponseCallback
     * @param array         $clientConfig
     */
    public function __construct(array $apiConfig)
    {
        if (!isset($apiConfig['storeUrl'])) {
            throw new BigCommerceConfigException('Missing storeUrl in api config');
        }
        $this->apiAccessToken = $apiConfig['accessToken'];
        
        $baseUrl = $apiConfig['storeUrl'] ;

        $clientConfig = array_merge(
            $this->defaultClientConfig,
            $apiConfig['clientConfig'] ?? []
        );

        $clientErrorResponseCallback = $apiConfig['clientErrorResponseCallback'] ?? null;
        $serverErrorResponseCallback = $apiConfig['serverErrorResponseCallback'] ?? null;

        parent::__construct(
            $baseUrl,
            '',
            $clientErrorResponseCallback,
            $serverErrorResponseCallback,
            $clientConfig
        );
    }

    /**
     * @param string $uri
     * @param array  $body
     * @param array  $queryParameters
     * @param array  $additionalHeaders
     * @return array|null
     * @throws BigCommerceClientException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleInternalServerException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleServerException
     */
    public function post($uri, array $body = [], array $queryParameters = [], array $additionalHeaders = [])
    {
        try {
            return parent::post($uri, $body, $queryParameters, $additionalHeaders);
        } catch (InguzzleClientException $inguzzleClientException) {
            throw new BigCommerceClientException(
                $inguzzleClientException->statusCode,
                $inguzzleClientException->getMessage(),
                $inguzzleClientException->getCode(),
                $inguzzleClientException
            );
        }
    }


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
