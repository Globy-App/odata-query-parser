<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use PHPUnit\Framework\TestCase;

final class SelectTest extends TestCase
{
    public function testShouldReturnSelectColumns(): void
    {
        $expected = ["select" => ["name", "type", "userId"]];
        $actual = OdataQueryParser::parse('https://example.com/users?$select=name,type,userId');

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnSelectColumnsIfFilledWithSpaces(): void
    {
        $expected = ["select" => ["name", "type", "userId"]];
        $actual = OdataQueryParser::parse('https://example.com/api/user?$select=%20name,%20type%20,userId%20');

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnTheColumnsInNonDollarMode(): void
    {
        $expected = ["select" => ["name", "type", "userId"]];
        $actual = OdataQueryParser::parse('https://example.com/?select=name,type,userId', false);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnTheColumnsIfFilledWithSpacesInNonDollarMode(): void
    {
        $expected = ["select" => ["name", "type", "userId"]];
        $actual = OdataQueryParser::parse('https://example.com/api/user?select=%20name,%20type%20,userId%20', false);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfNoColumnFound(): void
    {
        $expected = [];
        $actual = OdataQueryParser::parse('https://example.com/?$select=');

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAnEmptyArrayIfNoColumnFoundInNonDollarMode(): void
    {
        $expected = [];
        $actual = OdataQueryParser::parse('https://example.com/?select=');

        $this->assertEquals($expected, $actual);
    }
}