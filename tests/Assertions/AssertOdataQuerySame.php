<?php

namespace OdataQueryParserTests\Assertions;

use GlobyApp\OdataQueryParser\OdataQuery;
use PHPUnit\Framework\Constraint\Constraint;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;

/**
 * Trait AssertOdataQuerySame
 * @mixin Constraint
 */
trait AssertOdataQuerySame
{
    public function assertOdataQuerySame(OdataQuery $expected, ?OdataQuery $actual, string $message = ''): void
    {
        assertNotNull($actual, $message);
        assertEquals($expected->getFilter(), $actual->getFilter(), $message);
        assertSame(count($expected->getFilter()), count($actual->getFilter()), $message);

        for ($i = 0; $i < count($expected->getFilter()); $i++) {
            assertSame($expected->getFilter()[$i]->getValue(), $actual->getFilter()[$i]->getValue(), $message);
            assertSame($expected->getFilter()[$i]->getProperty(), $actual->getFilter()[$i]->getProperty(), $message);
            assertSame($expected->getFilter()[$i]->getOperator(), $actual->getFilter()[$i]->getOperator(), $message);
        }

        assertEquals($expected->getSelect(), $actual->getSelect(), $message);
        assertSame(count($expected->getSelect()), count($actual->getSelect()), $message);

        for ($i = 0; $i < count($expected->getSelect()); $i++) {
            assertSame($expected->getSelect()[$i], $actual->getSelect()[$i]);
        }

        assertEquals($expected->getOrderBy(), $actual->getOrderBy(), $message);
        assertSame(count($expected->getOrderBy()), count($actual->getOrderBy()), $message);

        for ($i = 0; $i < count($expected->getOrderBy()); $i++) {
            assertSame($expected->getOrderBy()[$i]->getProperty(), $actual->getOrderBy()[$i]->getProperty());
            assertSame($expected->getOrderBy()[$i]->getDirection(), $actual->getOrderBy()[$i]->getDirection());
        }

        assertSame($expected->getTop(), $actual->getTop(), $message);
        assertSame($expected->getSkip(), $actual->getSkip(), $message);
        assertSame($expected->getCount(), $actual->getCount(), $message);
    }
}