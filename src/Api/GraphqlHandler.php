<?php

namespace Ingelby\Bigcommerce\Api;

use Carbon\Carbon;
use Ingelby\Bigcommerce\Models\ApiTokenModel;

class GraphqlHandler extends AbstractHandler
{
    /**
     * @param int      $channelId
     * @param int|null $expiresAt
     * @param array    $allowedCorsOrigins
     * @return ApiTokenModel
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleClientException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleInternalServerException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleServerException
     */
    public function query(
        $query,
        string $token
    )
    {

        $response = $this->post(
            '/graphql',
            [
                'query' => $query,
            ],
            [],
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );

        return $response;
    }
}
