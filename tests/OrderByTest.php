<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class OrderByTest extends TestCase
{
    public function testShouldReturnThePropertyInTheOrderBy(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnAllThePropertiesInTheOrderBy(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
            new OdataQueryParser\Datatype\OrderByClause('bar', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo,bar');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnThePropertyInTheOrderByEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=%20foo%20');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnAnEmptyArrayIfOrderByIsEmpty(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnOrderByPropertyInAscDirectionIfSpecified(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo%20asc');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnOrderByPropertyInAscDirectionIfSpecifiedEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=%20foo%20%20%20asc%20');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecified(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo%20desc');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecifiedEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=%20foo%20%20%20desc%20');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldThrowExceptionIfDirectionInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Direction should be either asc or desc");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo%20ascendant');
    }

    public function testShouldReturnThePropertyInTheOrderByInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnThePropertyInTheOrderByInNonDollarModeEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=%20foo%20', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnAnEmptyArrayIfOrderByIsEmptyInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnOrderByPropertyInAscDirectionIfSpecifiedInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo%20asc', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnOrderByPropertyInAscDirectionIfSpecifiedInNonDollarModeEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=%20foo%20%20%20asc%20', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecifiedInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo%20desc', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecifiedInNonDollarModeEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=%20foo%20%20%20desc%20', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getOrderBy(), $actual?->getOrderBy());
    }

    public function testShouldThrowExceptionIfDirectionInvalidInNonDollarMode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Direction should be either asc or desc");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo%20ascendant', false);
    }
}