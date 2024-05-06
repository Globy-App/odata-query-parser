<?php

namespace GlobyApp\OdataQueryParser\Datatype;

use GlobyApp\OdataQueryParser\Enum\OrderDirection;

/**
 * @api Public get methods exposed to retrieve data from the result
 */
class OrderByClause
{
    private string $property;

    private OrderDirection $direction;

    /**
     * An order by clause with a field and an order direction
     *
     * @param string $property The property to be ordered
     * @param OrderDirection $direction The order in which to order the field by
     */
    public function __construct(string $property, OrderDirection $direction)
    {
        $this->property = $property;
        $this->direction = $direction;
    }

    /**
     * @return string The property on which to order
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return OrderDirection The direction in which to order the data of the property in this entry
     */
    public function getDirection(): OrderDirection
    {
        return $this->direction;
    }
}
