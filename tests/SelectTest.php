<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use PHPUnit\Framework\TestCase;

final class SelectTest extends TestCase
{
    public function testShouldReturnSelectColumns(): void
    {
        $expected = new OdataQueryParser\OdataQuery(["name", "type", "userId"]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/users?$select=name,type,userId');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSelect(), $actual->getSelect());
    }

    public function testShouldReturnSelectColumnsIfFilledWithSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery(["name", "type", "userId"]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$select=%20name,%20type%20,userId%20');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSelect(), $actual->getSelect());
    }

    public function testShouldReturnTheColumnsInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery(["name", "type", "userId"]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/?select=name,type,userId', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSelect(), $actual->getSelect());
    }

    public function testShouldReturnTheColumnsIfFilledWithSpacesInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery(["name", "type", "userId"]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?select=%20name,%20type%20,userId%20', false);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSelect(), $actual->getSelect());
    }

    public function testShouldReturnAnEmptyArrayIfNoColumnFound(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/?$select=');

        $this->assertEquals($expected->getSelect(), $actual->getSelect());
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfNoColumnFoundInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery();
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/?select=');

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected->getSelect(), $actual->getSelect());
    }
}