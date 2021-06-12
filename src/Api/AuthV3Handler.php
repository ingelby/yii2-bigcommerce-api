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
     * @param int      $channelId
     * @param int|null $expiresAt
     * @param array    $allowedCorsOrigins
     * @return ApiTokenModel
     * @throws \Ingelby\Bigcommerce\Exceptions\BigCommerceClientException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleInternalServerException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleServerException
     */
    public function generateApiToken(
        int $channelId = 1,
        int $expiresAt = null,
        array $allowedCorsOrigins = []
    ) {

        if (null === $expiresAt) {
            $expiresAt = Carbon::now()->addMinute()->getTimestamp();
        }
        $response = $this->post(
            '/storefront/api-token',
            [
                'channel_id'          => $channelId,
                'expires_at'          => $expiresAt,
                'allowed_cors_origins' => $allowedCorsOrigins,
            ],
            [],
            [
                Headers::X_AUTH_TOKEN => $this->apiAccessToken,
            ]
        );

        $apiTokenModel = new ApiTokenModel();
        $apiTokenModel->setAttributes($response['data'] ?? []);
        return $apiTokenModel;
    }
}
