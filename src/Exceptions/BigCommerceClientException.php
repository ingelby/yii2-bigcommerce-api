<?php


namespace Ingelby\Bigcommerce\Exceptions;

use Throwable;

class BigCommerceClientException extends BigCommerceBaseException
{

    public function __construct($httpStatusCode, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}