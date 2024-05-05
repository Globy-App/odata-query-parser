<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use PHPUnit\Framework\TestCase;

final class CountTest extends TestCase
{
    public function testShouldReturnCountTrueIfKeyFilledWithTrue(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], true);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$count=1');

        $this->assertEquals($expected, $actual);
        $this->assertTrue($actual->getCount());
    }

    public function testShouldReturnCountTrueIfKeyFilledWithTrueAndSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], true);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.om/api/user?$count=%201%20');

        $this->assertEquals($expected, $actual);
        $this->assertTrue($actual->getCount());
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalse(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], false);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$count=0');

        $this->assertEquals($expected, $actual);
        $this->assertFalse($actual->getCount());
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalseAndSpaces(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], false);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$count=%200%20');

        $this->assertEquals($expected, $actual);
        $this->assertFalse($actual->getCount());
    }

    public function testShouldReturnCountTrueIfKeyFillWithTrueInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], true);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?count=1", false);

        $this->assertEquals($expected, $actual);
        $this->assertTrue($actual->getCount());
    }

    public function testShouldReturnCountTrueIfKeyFilledWithTrueAndSpacesInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], true);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?count=%201%20', false);

        $this->assertEquals($expected, $actual);
        $this->assertTrue($actual->getCount());
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalseInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], false);
        $actual = OdataQueryParser\OdataQueryParser::parse("https://example.com/api/user?count=0", false);

        $this->assertEquals($expected, $actual);
        $this->assertFalse($actual->getCount());
    }

    public function testShouldNotReturnCountIfKeyFilledWithFalseAndSpacesInNonDollarMode(): void
    {
        $expected = new OdataQueryParser\OdataQuery([], false);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?count=%200%20', false);

        $this->assertEquals($expected, $actual);
        $this->assertFalse($actual->getCount());
    }
}