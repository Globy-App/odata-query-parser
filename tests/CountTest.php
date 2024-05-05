<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use PHPUnit\Framework\TestCase;

final class CountTest extends TestCase
{
    public function testShouldReturnCountTrueIfKeyFilledWithTrue(): void
    {
        $expected = ["count" => true];
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$count=1');

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnCountTrueIfKeyFilledWithTrueAndSpaces(): void
    {
        $expected = ["count" => true];
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.om/api/user?$count=%201%20');

        $this->assertEquals($expected, $actual);
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalse(): void
    {
        $expected = [];
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$count=0');

        $this->assertEquals($expected, $actual);
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalseAndSpaces(): void
    {
        $expected = [];
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$count=%200%20');

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnCountTrueIfKeyFillWithTrueInNonDollarMode(): void
    {
        $expected = ["count" => true];
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?count=1", false);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnCountTrueIfKeyFilledWithTrueAndSpacesInNonDollarMode(): void
    {
        $expected = ["count" => true];
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?count=%201%20', false);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalseInNonDollarMode(): void
    {
        $expected = [];
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?count=0", false);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalseAndSpacesInNonDollarMode(): void
    {
        $expected = [];
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?count=%200%20', false);

        $this->assertEquals($expected, $actual);
    }
}