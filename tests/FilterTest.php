<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;

final class FilterTest extends BaseTestCase
{
    public function testShouldReturnEmptyArrayIfEmptyFilter(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=");

        $this->assertOdataQuerySame($expected, $actual);
    }
    public function testShouldReturnEmptyArrayIfEmptyFilterWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=%20%20");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnEqualClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnEqualClauseMixedCase(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name%20eQ%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
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

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnNotEqualClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name', OdataQueryParser\Enum\FilterOperator::NOT_EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name%20ne%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnNotEqualClauseMixedCase(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name', OdataQueryParser\Enum\FilterOperator::NOT_EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name%20Ne%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnGreaterThanClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::GREATER_THAN, 20),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20gt%2020");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnGreaterThanClauseMixedCase(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::GREATER_THAN, 20),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20Gt%2020");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnGreaterOrEqualToClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::GREATER_THAN_EQUALS, 21),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20ge%2021");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnGreaterOrEqualToClauseMixedCase(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::GREATER_THAN_EQUALS, 21),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20gE%2021");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnLowerThanClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::LESS_THAN, 42),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20lt%2042");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnLowerThanClauseMixedCase(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::LESS_THAN, 42),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20lT%2042");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnLowerOrEqualToClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::LESS_THAN_EQUALS, 42),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20le%2042");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnLowerOrEqualToClauseMixedCase(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::LESS_THAN_EQUALS, 42),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20Le%2042");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnInClause(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('city', OdataQueryParser\Enum\FilterOperator::IN,
                ["Paris", "Malaga", "London"]),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=city%20in%20(%27Paris%27,%20%27Malaga%27,%20%27London%27)");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnInClauseMixedCase(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('city', OdataQueryParser\Enum\FilterOperator::IN,
                ["Paris", "Malaga", "London"]),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=city%20In%20(%27Paris%27,%20%27Malaga%27,%20%27London%27)");

        $this->assertOdataQuerySame($expected, $actual);
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

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnIntegersIfInIntegers(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::IN,
                [21, 31, 41]),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20in%20(21,%2031,%2041)");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnIntegersIfInFloats(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('age', OdataQueryParser\Enum\FilterOperator::IN,
                [21.42, 31.42, 41.42]),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=age%20in%20(21.42,%2031.42,%2041.42)");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnFloatIfCheckingInFloat(): void
    {
        $inArray = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=taxRate%20in%20(19.5,%2020)")?->getFilter()[0]->getValue();
        $this->assertIsArray($inArray);
        $this->assertArrayHasKey(0, $inArray);
        $this->assertIsFloat($inArray[0]);
    }

    public function testBooleanTrueValue(): void
    {
        $bool = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=taxRate%20eq%20true")?->getFilter()[0]->getValue();
        $this->assertTrue($bool);
    }

    public function testBooleanFalseValue(): void
    {
        $bool = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=taxRate%20eq%20false")?->getFilter()[0]->getValue();
        $this->assertFalse($bool);
    }

    public function testInvalidOperator(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Filter operator should be eq, ne, gt, ge, lt, le or in");

        OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=taxRate%20GQ%20false");
    }

    public function testInvalidOperatorInNonDollarMode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Filter operator should be eq, ne, gt, ge, lt, le or in.");

        OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?filter=taxRate%20GQ%20false", false);
    }

    public function testInvalidStructure(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("A filter clause is invalid and resulted in a split of 0 terms.");

        OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=taxRate%20le");
    }

    public function testInvalidStructureInNonDollarMode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("A filter clause is invalid and resulted in a split of 0 terms.");

        OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?filter=taxRate%20le", false);
    }

    // Tests created on the basis of https://github.com/Globy-App/odata-query-parser/issues/7
    public function testDotInProperty(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name.bar', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name.bar%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testSlashInProperty(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name/bar', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name%2Fbar%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testBackslashInProperty(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name\bar', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name%5Cbar%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testDashInProperty(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name-bar', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name-bar%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testUnderscoreInProperty(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name_bar', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name_bar%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testNonWordInProperty(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('bår', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=b%C3%A5r%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testNumbersInProperty(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('name7378', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=name7378%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testPropertyPrecedingTrim(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('bar', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=%20bar%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testPropertySucceedingTrim(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('bar', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=bar%20%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testPropertyTrim(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], null, null, null, [], [
            new OdataQueryParser\Datatype\FilterClause('bar', OdataQueryParser\Enum\FilterOperator::EQUALS, 'foo'),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?\$filter=%20bar%20%20eq%20%27foo%27");

        $this->assertOdataQuerySame($expected, $actual);
    }
}
