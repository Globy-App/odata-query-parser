<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SkipTest extends TestCase
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

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSkip(), $actual?->getSkip());
    }

    public function testShouldContainTheSkipValueIfProvidedInTheQueryParameterAndFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, 42);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$skip=%2042%20');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSkip(), $actual?->getSkip());
    }

    public function testShouldContainAnEmptyArrayIfSkipParameterIsEmpty(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$skip=');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSkip(), $actual?->getSkip());
    }

    public function testShouldNotThrowExceptionIfSkipIsEqualToZero(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, 0);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$skip=0');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSkip(), $actual?->getSkip());
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

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSkip(), $actual?->getSkip());
    }

    public function testShouldContainTheSkipValueIfProvidedInTheQueryParameterAndFilledWithSpacesInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, 42);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?skip=%2042%20', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSkip(), $actual?->getSkip());
    }

    public function testShouldContainAnEmptyArrayIfSkipParameterIsEmptyInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?skip=', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSkip(), $actual?->getSkip());
    }
}