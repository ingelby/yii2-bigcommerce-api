<?php


namespace Ingelby\Bigcommerce\Models;


use Carbon\Carbon;

class ApiTokenModel extends AbstractBigcommerceModel
{
    public ?string $token = null;
    public ?Carbon $expiresAt = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'token',
                    'expiresAt',
                ],
                'safe',
            ],
        ];
    }
}