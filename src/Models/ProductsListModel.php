<?php


namespace Ingelby\Bigcommerce\Models;


class ProductsListModel extends AbstractBigcommerceModel
{
    public PageInfoModel $pageInfo;
    /**
     * @var EdgeModel[]
     */
    public array $edges = [];

    public function __construct($config = [])
    {
        $this->pageInfo = new PageInfoModel();
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

                ],
                'safe',
            ],
        ];
    }

    /**
     * @param array $productList
     */
    public function mapRaw(array $productList)
    {
        $this->mapPageInfo($productList['pageInfo'] ?? []);
        $this->mapRawEdges($productList['edges'] ?? []);
    }

    /**
     * @param array $rawEdgesData
     */
    protected function mapPageInfo(array $rawPageInfo): void
    {
        $this->pageInfo->setAttributes($rawPageInfo);
    }

    /**
     * @param array $rawEdgesData
     */
    protected function mapRawEdges(array $rawEdgesData): void
    {
        foreach ($rawEdgesData as $rawEdge) {
            $edge = new EdgeModel();
            $edge->cursor = $rawEdge['cursor'] ?? null;
            $edge->mapRawProduct($rawEdge['node'] ?? []);
            $this->edges[] = $edge;
        }
    }
}