<?php


namespace Ingelby\Bigcommerce\Exceptions;

use Throwable;

class BigCommerceClientException extends BigCommerceBaseException
{
    public int $httpStatusCode;

    public function __construct(int $httpStatusCode, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, $code, $previous);
    }

}