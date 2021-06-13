<?php


namespace Ingelby\Bigcommerce\Models;


use GraphQL\Query;
use yii\helpers\Url;

class ProductModel extends AbstractBigcommerceModel
{
    public ?string $id = null;
    public ?int $entityId = null;
    public ?string $sku = null;
    public ?string $path = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $plainTextDescription = null;
    public ?string $warranty = null;
    public ?int $minPurchaseQuantity = null;
    public ?int $maxPurchaseQuantity = null;
    public ?string $addToCartUrl = null;

    public PriceModel $price;
    public PriceModel $salePrice;
    public PriceModel $retailPrice;

    public function __construct($config = [])
    {
        $this->price = new PriceModel();
        $this->salePrice = new PriceModel();
        $this->retailPrice = new PriceModel();
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'entityId',
                    'sku',
                    'path',
                    'name',
                    'description',
                    'plainTextDescription',
                    'warranty',
                    'minPurchaseQuantity',
                    'maxPurchaseQuantity',
                    'addToCartUrl',
                ],
                'safe',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function getDefaultGraphqlSelectionSet(): array
    {
        return
            [
                'id',
                'entityId',
                'sku',
                'path',
                'name',
                'description',
                (new Query('prices'))
                    ->setSelectionSet(
                        [
                            PriceModel::getDefaultGraphqlQueryNode(),
                            PriceModel::getDefaultGraphqlQueryNode('salePrice'),
                            PriceModel::getDefaultGraphqlQueryNode('retailPrice'),
                        ]
                    ),
            ];
    }

    /**
     * @return string
     */
    public function toRoutePath(): string
    {
        return Url::toRoute('/shop/product' . $this->path);
    }

    public function mapRawPrices(array $rawPrices): void
    {
        $this->mapRawRetailPrice($rawPrices['price'] ?? []);
        $this->mapRawRetailPrice($rawPrices['salePrice'] ?? []);
        $this->mapRawPrice($rawPrices['retailPrice'] ?? []);
    }

    protected function mapRawPrice(array $rawPrice): void
    {
        $this->price->setAttributes($rawPrice);
        $this->price->unit = $rawPrice['currencyCode'] ?? null;
    }

    protected function mapRawSalePrice(array $rawPrice): void
    {
        $this->salePrice->setAttributes($rawPrice);
        $this->salePrice->unit = $rawPrice['currencyCode'] ?? null;
    }

    protected function mapRawRetailPrice(array $rawPrice): void
    {
        $this->retailPrice->setAttributes($rawPrice);
        $this->retailPrice->unit = $rawPrice['currencyCode'] ?? null;
    }
}
