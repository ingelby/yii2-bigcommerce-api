<?php


namespace Ingelby\Bigcommerce\Models;


use GraphQL\Query;

class MeasurementModel extends AbstractBigcommerceModel
{
    public ?float $value = null;
    public ?string $unit = null;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'value',
                    'unit',
                ],
                'safe',
            ],
        ];
    }
}
