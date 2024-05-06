<?php

namespace OdataQueryParserTests;

use OdataQueryParserTests\Assertions\AssertOdataQuerySame;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    use AssertOdataQuerySame;
}