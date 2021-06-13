<?php


namespace Ingelby\Bigcommerce\Models;


class PageInfoModel extends AbstractBigcommerceModel
{
    public ?string $startCursor = null;
    public ?string $endCursor = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'startCursor',
                    'endCursor',
                ],
                'safe',
            ],
        ];
    }

}