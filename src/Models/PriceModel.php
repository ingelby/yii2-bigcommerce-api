<?php


namespace Ingelby\Bigcommerce\Models;


use GraphQL\Query;

class PriceModel extends MeasurementModel
{
    
    public function getHumanFriendlyPrice(): ?string
    {
        return $this->getCurrencySymbol() . $this->getFormattedPrice();
    }

    public function getFormattedPrice(): ?string
    {
        if (null === $this->value) {
            return null;
        }
        return number_format($this->value, 2);
    }

    public function getCurrencyCode(): ?string
    {
        return $this->unit;
    }

    public function getCurrencySymbol(): string
    {
        //@Todo, get this data from currency code....
        return '£';
    }

    /**
     * @return Query
     */
    public static function getDefaultGraphqlQueryNode(string $nodeName = 'price'): Query
    {
        return (new Query($nodeName))
            ->setSelectionSet(
                [
                    'value',
                    'currencyCode',
                ]
            );
    }
}
