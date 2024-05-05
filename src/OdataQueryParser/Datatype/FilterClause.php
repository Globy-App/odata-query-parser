<?php

namespace GlobyApp\OdataQueryParser\Datatype;

use PHPUnit\Framework\Constraint\Operator;

/**
 * @api Public get methods exposed to retrieve data from the result
 */
class FilterClause
{
    private string $property;

    private Operator $operator;

    private string $value;

    /**
     * A filter clause with a field, an operator and the value to filter with
     *
     * @param string $property The property that should be filtered
     * @param Operator $operator The filter operator used
     * @param string $value The value to filter the property on with the operator
     */
    public function __construct(string $property, Operator $operator, string $value)
    {
        $this->property = $property;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * @return string The property on which to filter
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return Operator The operator with which to filter
     */
    public function getOperator(): Operator
    {
        return $this->operator;
    }

    /**
     * @return string The value to filter the property on with the operator
     */
    public function getValue(): string
    {
        return $this->value;
    }
}