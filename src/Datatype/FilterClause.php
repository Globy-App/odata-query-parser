<?php

namespace GlobyApp\OdataQueryParser\Datatype;

use GlobyApp\OdataQueryParser\Enum\FilterOperator;

/**
 * @api Public get methods exposed to retrieve data from the result
 */
class FilterClause
{
    private string $property;

    private FilterOperator $operator;

    /**
     * @var int|float|string|bool|null|array<int|float|string|bool|null> $value
     */
    private int|float|string|bool|null|array $value;

    /**
     * A filter clause with a field, an operator and the value to filter with
     *
     * @param string $property The property that should be filtered
     * @param FilterOperator $operator The filter operator used
     * @param int|float|string|bool|null|array<int|float|string|bool|null> $value The value to filter the property on with the operator
     */
    public function __construct(string $property, FilterOperator $operator, int|float|string|bool|null|array $value)
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
     * @return FilterOperator The operator with which to filter
     */
    public function getOperator(): FilterOperator
    {
        return $this->operator;
    }

    /**
     * @return int|float|string|bool|null|array<int|float|string|bool|null> The value to filter the property on with the operator
     */
    public function getValue(): int|float|string|bool|null|array
    {
        return $this->value;
    }
}