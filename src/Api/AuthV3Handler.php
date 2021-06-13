<?php

namespace Ingelby\Bigcommerce\Api;

use Carbon\Carbon;
use Ingelby\Bigcommerce\Constants\Headers;
use Ingelby\Bigcommerce\Exceptions\BigCommerceConfigException;
use Ingelby\Bigcommerce\Models\ApiTokenModel;
use ingelby\toolbox\services\inguzzle\InguzzleHandler;

class AuthV3Handler extends AbstractV3Handler
{
    protected string $storeHash;

    /**
     * AuthV3Handler constructor.
     *
     * @param array $apiConfig
     * @throws BigCommerceConfigException
     */
    public function __construct(array $apiConfig)
    {
        if (!isset($apiConfig['storeHash'])) {
            throw new BigCommerceConfigException('Missing storeHash in api config');
        }

        $this->storeHash = $apiConfig['storeHash'];

        $apiConfig['uriPrefix'] = '/stores/' . $apiConfig['storeHash'] . '/v3';

        parent::__construct($apiConfig);
    }

    /**
     * @param Carbon|null $expiresAt
     * @param int      $channelId
     * @param array    $allowedCorsOrigins
     * @return ApiTokenModel
     * @throws \Ingelby\Bigcommerce\Exceptions\BigCommerceClientException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleInternalServerException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleServerException
     */
    public function generateApiToken(
        ?Carbon $expiresAt = null,
        int $channelId = 1,
        array $allowedCorsOrigins = []
    )
    {

        if (null === $expiresAt) {
            $expiresAt = Carbon::now()->addMinute();
        }
        $response = $this->post(
            '/storefront/api-token',
            [
                'channel_id'           => $channelId,
                'expires_at'           => $expiresAt->getTimestamp(),
                'allowed_cors_origins' => $allowedCorsOrigins,
            ],
            [],
            [
                Headers::X_AUTH_TOKEN => $this->apiAccessToken,
            ]
        );

        $apiTokenModel = new ApiTokenModel(
            [
                'expiresAt' => $expiresAt,
            ]
        );
        $apiTokenModel->setAttributes($response['data'] ?? []);
        return $apiTokenModel;
    }
}
