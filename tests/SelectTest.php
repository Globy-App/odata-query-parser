<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;

final class SelectTest extends BaseTestCase
{
    public function testShouldReturnSelectColumns(): void
    {
        $expected = new OdataQueryParser\OdataQuery(["name", "type", "userId"]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/users?$select=name,type,userId');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnSelectColumnsIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery(["name", "type", "userId"]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$select=%20name,%20type%20,userId%20');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnTheColumnsInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery(["name", "type", "userId"]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/?select=name,type,userId', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnTheColumnsIfFilledWithSpacesInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery(["name", "type", "userId"]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?select=%20name,%20type%20,userId%20', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfNoColumnFound(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/?$select=');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfNoColumnFoundInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/?select=');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfNoColumnFoundWithSpace(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/?$select=%20%20');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfNoColumnFoundInNonDollarModeWithSpace(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/?select=%20%20');

        $this->assertOdataQuerySame($expected, $actual);
    }
}