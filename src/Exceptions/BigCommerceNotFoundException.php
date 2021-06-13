<?php


namespace Ingelby\Bigcommerce\Exceptions;

use ingelby\toolbox\constants\HttpStatus;
use Throwable;

class BigCommerceNotFoundException extends BigCommerceClientException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(HttpStatus::NOT_FOUND, $message, $code, $previous);
    }

}