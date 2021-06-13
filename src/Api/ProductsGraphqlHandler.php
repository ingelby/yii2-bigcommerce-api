<?php

namespace Ingelby\Bigcommerce\Api;

use GraphQL\InlineFragment;
use GraphQL\Query;
use GraphQL\Variable;
use Ingelby\Bigcommerce\Exceptions\BigCommerceNotFoundException;
use Ingelby\Bigcommerce\Models\ProductModel;
use Ingelby\Bigcommerce\Models\ProductsListModel;
use function GuzzleHttp\Psr7\str;

class ProductsGraphqlHandler extends GraphqlHandler
{
    /**
     * @param array       $productSelectionSet
     * @param int         $perPage
     * @param string|null $cursor
     * @return ProductsListModel
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleClientException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleInternalServerException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleServerException
     */
    public function queryPaginateProducts(
        array $productSelectionSet,
        int $perPage = 5,
        ?string $cursor = null
    ): ProductsListModel
    {
        $gql = (new Query('site'))
            ->setOperationName('paginateProducts')
            ->setVariables(
                [
                    new Variable('pageSize', 'Int', false, $perPage),
                    new Variable('cursor', 'String', false, $cursor),
                ]
            )
            ->setSelectionSet(
                [
                    (new Query('products'))
                        ->setArguments(
                            [
                                'first' => '$pageSize',
                                'after' => '$cursor',
                            ]
                        )
                        ->setSelectionSet(
                            [
                                (new Query('pageInfo'))
                                    ->setSelectionSet(
                                        [
                                            'startCursor',
                                            'endCursor',
                                        ]
                                    ),
                                (new Query('edges'))
                                    ->setSelectionSet(
                                        [
                                            'cursor',
                                            (new Query('node'))
                                                ->setSelectionSet($productSelectionSet),
                                        ]
                                    ),
                            ]
                        ),

                ]
            );

        $response = $this->query($gql);
        $productListModel = new ProductsListModel();
        $productListModel->mapRaw($response['data']['site']['products'] ?? []);
        return $productListModel;
    }


    /**
     * @param string $urlPath
     * @param array  $productSelectionSet
     * @return ProductModel
     * @throws BigCommerceNotFoundException
     * @throws \Ingelby\Bigcommerce\Exceptions\BigCommerceClientException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleInternalServerException
     * @throws \ingelby\toolbox\services\inguzzle\exceptions\InguzzleServerException
     */
    public function queryProductByUrlPath(string $urlPath, array $productSelectionSet): ProductModel
    {
        $variables = [
            'urlPath' => $urlPath,
        ];

        $gql = (new Query('site'))
            ->setOperationName('LookUpUrl')
            ->setVariables(
                [
                    new Variable('urlPath', 'String', true, $urlPath),
                ]
            )
            ->setSelectionSet(
                [
                    (new Query('route'))
                        ->setArguments(
                            [
                                'path' => '$urlPath',
                            ]
                        )
                        ->setSelectionSet(
                            [
                                (new Query('node'))
                                    ->setSelectionSet(
                                        [
                                            '__typename',
                                            'id',
                                            (new InlineFragment('Product'))
                                                ->setSelectionSet($productSelectionSet),
                                        ]
                                    ),
                            ]
                        ),

                ]
            );

        $response = $this->query($gql, $variables);
        $productModel = new ProductModel();
        if (!isset($response['data']['site']['route']['node'])) {
            throw new BigCommerceNotFoundException('Path: ' . $urlPath . ' does not exist');
        }
        $rawData = $response['data']['site']['route']['node'];
        $productModel->setAttributes($rawData);
        $productModel->mapRawPrices($rawData['prices'] ?? []);
        return $productModel;
    }
}
