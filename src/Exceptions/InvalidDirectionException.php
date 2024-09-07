<?php

namespace GlobyApp\OdataQueryParser\Exceptions;

class InvalidDirectionException extends \InvalidArgumentException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
