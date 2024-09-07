<?php

namespace GlobyApp\OdataQueryParser\Exceptions;

class InvalidFilterOperatorException extends \InvalidArgumentException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
