<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ParseTest extends TestCase {
	public function testShouldReturnExceptionIfUrlIsEmpty(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("URL should be a valid, full URL");

        OdataQueryParser\OdataQueryParser::parse('');
	}

	public function testShouldReturnExceptionIfUrlIsNotValid(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("URL should be a valid, full URL");

        OdataQueryParser\OdataQueryParser::parse('example.com');
	}

	public function testShouldReturnAnEmptyArrayIfNoQueryParameters(): void {
		$expected = null;
		$actual = OdataQueryParser\OdataQueryParser::parse("https://example.com");

		$this->assertEquals($expected, $actual);
	}

    public function testShouldReturnMultipleValues(): void
    {
        $expected = new OdataQueryParser\OdataQuery(['firstName', 'lastName'], null, 10, 10, [
            new OdataQueryParser\Datatype\OrderByClause('id', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$select=firstName,lastName&$orderby=id&$top=10&$skip=10');

        $this->assertEquals($expected, $actual);
    }
}
