<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use PHPUnit\Framework\TestCase;

final class FilterTest extends TestCase
{
    public function testShouldReturnEmptyArrayIfEmptyFilter(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnEqualClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name%20eq%20%27foo%27");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnEqualClauseWithFloat(): void
    {
        $this->assertIsFloat(OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20eq%2042.42")?->getFilter()[0]->getValue());
    }

    public function testShouldReturnEqualClauseWithInteger(): void
    {
        $this->assertIsInt(OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20eq%2042")?->getFilter()[0]->getValue());
    }

    public function testShouldReturnEqualClauseWithSpacedStrings(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name', OdataQueryParser\Enum\FilterOperator::EQUALS, ' foo '),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name%20eq%20%27%20foo%20%27");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnNotEqualClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name', OdataQueryParser\Enum\FilterOperator::NOT_EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name%20ne%20%27foo%27");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnGreaterThanClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::GREATER_THAN, 20),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20gt%2020");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnGreaterOrEqualToClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::GREATER_THAN_EQUALS, 21),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20ge%2021");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnLowerThanClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::LESS_THAN, 42),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20lt%2042");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnLowerOrEqualToClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::LESS_THAN_EQUALS, 42),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20le%2042");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnInClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('city', OdataQueryParser\Enum\FilterOperator::IN,
                ["Paris", "Malaga", "London"]),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=city%20in%20(%27Paris%27,%20%27Malaga%27,%20%27London%27)");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnMultipleClauseSeparatedByTheAndOperator(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('city', OdataQueryParser\Enum\FilterOperator::IN,
                [" Paris", " Malaga ", "London "]),
            new OdataQueryParser\Datatype\FilterClause('name', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::GREATER_THAN, 20),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=city%20in%20(%27%20Paris%27,%20%27%20Malaga%20%27,%20%27London%20%27)%20and%20name%20eq%20%27foo%27%20and%20age%20gt%2020");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnIntegersIfInIntegers(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::IN,
                [21, 31, 41]),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20in%20(21,%2031,%2041)");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnIntegersIfInFloats(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::IN,
                [21.42, 31.42, 41.42]),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20in%20(21.42,%2031.42,%2041.42)");

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnFloatIfCheckingInFloat(): void
    {
        $inArray = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=taxRate%20in%20(19.5,%2020)")?->getFilter()[0]->getValue();
        $this->assertIsArray($inArray);
        $this->assertArrayHasKey(0, $inArray);
        $this->assertIsFloat($inArray[0]);
    }
}
