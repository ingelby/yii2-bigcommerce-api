<?php

namespace Ingelby\Bigcommerce\Api;

use Carbon\Carbon;
use GraphQL\Query;
use Ingelby\Bigcommerce\Models\ApiTokenModel;

class GraphqlHandler extends AbstractHandler
{
    protected ApiTokenModel $apiTokenModel;

    /**
     * GraphqlHandler constructor.
     *
     * @param ApiTokenModel $apiTokenModel
     * @param array         $apiConfig
     * @throws \Ingelby\Bigcommerce\Exceptions\BigCommerceConfigException
     */
    public function __construct(ApiTokenModel $apiTokenModel, array $apiConfig)
    {
        $this->apiTokenModel = $apiTokenModel;
        parent::__construct($apiConfig);
    }

    /**
     * @param Query $query
     * @param array $variables
     * @return array|null
     * @throws \Ingelby\Bigcommerce\Exceptions\BigCommerceClientException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleInternalServerException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleServerException
     */
    public function query(Query $query, array $variables = []): ?array
    {
        return $this->queryRaw((string)$query, $variables);
    }

    /**
     * @param string $query
     * @param array  $variables
     * @return array|null
     * @throws \Ingelby\Bigcommerce\Exceptions\BigCommerceClientException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleInternalServerException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleServerException
     */
    public function queryRaw(string $query, array $variables = []): ?array
    {

        $response = $this->post(
            '/graphql',
            [
                'query'     => $query,
                'variables' => $variables,
            ],
            [],
            [
                'Authorization' => 'Bearer ' . $this->apiTokenModel->token,
            ]
        );

        return $response;
    }

    /**
     * @return ApiTokenModel
     */
    public function getApiAccessToken(): ApiTokenModel
    {
        return $this->apiAccessToken;
    }

    /**
     * @param ApiTokenModel $apiAccessToken
     */
    public function setApiAccessToken(ApiTokenModel $apiAccessToken): void
    {
        $this->apiAccessToken = $apiAccessToken;
    }
}
