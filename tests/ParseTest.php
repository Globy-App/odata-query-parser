<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser;
use InvalidArgumentException;

final class ParseTest extends BaseTestCase {
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
		$expected = new OdataQueryParser\OdataQuery();
		$actual = OdataQueryParser\OdataQueryParser::parse("https://example.com");

		$this->assertEquals($expected, $actual);
	}

    public function testShouldReturnMultipleValues(): void
    {
        $expected = new OdataQueryParser\OdataQuery(['firstName', 'lastName'], null, 10, 10, [
            new OdataQueryParser\Datatype\OrderByClause('id', OdataQueryParser\Enum\OrderDirection::ASC),
        ]);
        $actual = OdataQueryParser\OdataQueryParser::parse('https://example.com/api/user?$select=firstName,lastName&$orderby=id&$top=10&$skip=10');

        $this->assertOdataQuerySame($expected, $actual);
    }
}
