<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use InvalidArgumentException;

final class SkipTest extends BaseTestCase
{
    public function testShouldThrowAnInvalidArgumentExceptionIfSkipParameterIsLowerThanZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Skip should be greater or equal to zero");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/?$skip=-1');
    }

    public function testShouldThrowAnInvalidArgumentExceptionIfSkipIsNotAnInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid datatype for \$skip");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/?$skip=test');
    }

    public function testShouldContainTheSkipValueIfProvidedInQueryParameters(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, 42);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$skip=42');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldContainTheSkipValueIfProvidedInTheQueryParameterAndFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, 42);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$skip=%2042%20');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldContainAnEmptyArrayIfSkipParameterIsEmpty(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$skip=');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldNotThrowExceptionIfSkipIsEqualToZero(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, 0);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$skip=0');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldNotThrowExceptionIfSkipIsEqualToZeroWithSpace(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, 0);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$skip=%200');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAnIntegerForTheSkipValue(): void
    {
        $this->assertIsInt(OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$skip=42')?->getSkip());
    }

    public function testShouldThrowAnInvalidArgumentExceptionIfSkipParameterIsLowerThanZeroInNonDollarMode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Skip should be greater or equal to zero");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/?skip=-1', false);
    }

    public function testShouldThrowAnInvalidArgumentExceptionIfSkipIsNotAnIntegerInNonDollarMode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid datatype for skip");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/?skip=test', false);
    }

    public function testShouldContainTheSkipValueIfProvidedInQueryParametersInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, 42);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?skip=42', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldContainTheSkipValueIfProvidedInTheQueryParameterAndFilledWithSpacesInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, 42);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?skip=%2042%20', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldContainAnEmptyArrayIfSkipParameterIsEmptyInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?skip=', false);

        $this->assertOdataQuerySame($expected, $actual);
    }
}