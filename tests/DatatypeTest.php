<?php

namespace OdataQueryParserTests;

use GlobyApp\OdataQueryParser\Datatype\FilterClause;
use GlobyApp\OdataQueryParser\Datatype\OrderByClause;
use GlobyApp\OdataQueryParser\Enum\FilterOperator;
use GlobyApp\OdataQueryParser\Enum\OrderDirection;

class DatatypeTest extends BaseTestCase
{
    public function testFilterClauseContinuitySingleValue(): void
    {
        $clause = new FilterClause("property_name", FilterOperator::IN, "test_value");

        $this->assertEquals("property_name", $clause->getProperty());
        $this->assertEquals(FilterOperator::IN, $clause->getOperator());
        $this->assertEquals("test_value", $clause->getValue());
    }

    public function testFilterClauseContinuityArray(): void
    {
        $clause = new FilterClause("property_name", FilterOperator::GREATER_THAN, ["string", "false", true, 20, 294.29, null]);

        $this->assertEquals("property_name", $clause->getProperty());
        $this->assertEquals(FilterOperator::GREATER_THAN, $clause->getOperator());
        $this->assertEquals(["string", "false", true, 20, 294.29, null], $clause->getValue());
    }

    public function testOrderByClauseContinuity(): void
    {
        $clause = new OrderByClause("property_name", OrderDirection::DESC);

        $this->assertEquals("property_name", $clause->getProperty());
        $this->assertEquals(OrderDirection::DESC, $clause->getDirection());
    }
}