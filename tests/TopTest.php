<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TopTest extends TestCase
{
    public function testShouldThrowAnInvalidArgumentExceptionIfTopQueryParameterIsLowerThanZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Top should be greater or equal to zero");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/users?$top=-1');
    }

    public function testShouldNotThrowExceptionIfTopQueryParameterIsEqualToZero(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, 0);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user/?$top=0');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getTop(), $actual->getTop());
    }

    public function testShouldThrowAnExceptionIfTopQueryParameterIsLowerThanZeroAndFilledWithSpaces(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Top should be greater or equal to zero");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/users?$top=%20-1%20');
    }

    public function testShouldThrowAnInvalidArgumentExceptionIfTopIsNotAnInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid datatype for \$top");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/?$top=foo');
    }

    public function testShouldReturnTheTopValueIfProvidedInTheQueryParameters(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, 42);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/?$top=42');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getTop(), $actual->getTop());
    }

    public function testShouldReturnAnIntegerTopValue(): void
    {
        $this->assertIsInt(OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$top=42')?->getTop());
    }

    public function testShouldReturnTheTopValueIfProvidedInTheQueryParametersAndFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, 42);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$top=%2042%20');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getTop(), $actual->getTop());
    }
}