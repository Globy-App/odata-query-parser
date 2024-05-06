<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use InvalidArgumentException;

final class OrderByTest extends BaseTestCase
{
    public function testShouldReturnThePropertyInTheOrderBy(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAllThePropertiesInTheOrderBy(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
            new OdataQueryParser\Datatype\OrderByClause('bar', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo,bar');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnThePropertyInTheOrderByEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=%20foo%20');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfOrderByIsEmpty(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfOrderByIsEmptyWithSpace(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=%20%20');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnOrderByPropertyInAscDirectionIfSpecified(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo%20asc');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnOrderByPropertyInAscDirectionIfSpecifiedEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=%20foo%20%20%20asc%20');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecified(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo%20desc');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecifiedMixedCase(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo%20dESc');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecifiedEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=%20foo%20%20%20desc%20');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldThrowExceptionIfDirectionInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Direction should be either asc or desc");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo%20ascendant');
    }

    public function testShouldThrowExceptionIfTooManyArguments(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("An order by condition is invalid and resulted in a split of 3 terms.");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$orderby=foo%20asc%20third');
    }

    public function testShouldReturnThePropertyInTheOrderByInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnThePropertyInTheOrderByInNonDollarModeEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=%20foo%20', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfOrderByIsEmptyInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfOrderByIsEmptyInNonDollarModeWithSpace(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=%20%20', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnOrderByPropertyInAscDirectionIfSpecifiedInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo%20asc', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnOrderByPropertyInAscDirectionIfSpecifiedInNonDollarModeEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=%20foo%20%20%20asc%20', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecifiedInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo%20desc', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecifiedInNonDollarModeMixedCase(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo%20dESC', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnThePropertyInDescDirectionIfSpecifiedInNonDollarModeEvenIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [
            new OdataQueryParser\Datatype\OrderByClause('foo', OdataQueryParser\Enum\OrderDirection::DESC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=%20foo%20%20%20desc%20', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldThrowExceptionIfDirectionInvalidInNonDollarMode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Direction should be either asc or desc");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo%20ascendant', false);
    }

    public function testShouldThrowExceptionIfTooManyArgumentsInNonDollarMode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("An order by condition is invalid and resulted in a split of 3 terms.");

        OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?orderby=foo%20asc%20third', false);
    }
}