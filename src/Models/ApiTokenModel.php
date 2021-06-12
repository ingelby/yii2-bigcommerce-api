<?php


namespace Ingelby\Bigcommerce\Models;


class ApiTokenModel extends AbstractBigcommerceModel
{
    public ?string $token = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'token',
                ],
                'safe',
            ],
        ];
    }
}