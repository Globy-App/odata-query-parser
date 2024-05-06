<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;

final class CountTest extends BaseTestCase
{
    public function testShouldReturnCountTrueIfKeyFilledWithTrue(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], true);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$count=1');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnCountTrueIfKeyFilledWithTrueAndSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], true);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.om/api/user?$count=%201%20');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalse(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], false);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$count=0');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalseAndSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], false);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$count=%200%20');

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnCountTrueIfKeyFillWithTrueInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], true);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?count=1", false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldReturnCountTrueIfKeyFilledWithTrueAndSpacesInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], true);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?count=%201%20', false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalseInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], false);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?count=0", false);

        $this->assertOdataQuerySame($expected, $actual);
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalseAndSpacesInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], false);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?count=%200%20', false);

        $this->assertOdataQuerySame($expected, $actual);
    }
}