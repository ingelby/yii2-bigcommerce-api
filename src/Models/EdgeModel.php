<?php


namespace Ingelby\Bigcommerce\Models;


class EdgeModel extends AbstractBigcommerceModel
{
    public ?string $cursor = null;
    public ProductModel $product;

    /**
     * EdgeModel constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->product = new ProductModel();
        parent::__construct($config);
    }

    /**
     * @param array $rawProductNode
     */
    public function mapRawProduct(array $rawProductNode)
    {
        $this->product->setAttributes($rawProductNode);
        $this->product->mapRawPrices($rawProductNode['prices'] ?? []);
    }
}