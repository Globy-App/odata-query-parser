<?php

namespace GlobyApp\OdataQueryParser\Enum;

/**
 * @api Public get methods exposed to retrieve data from the result
 */
enum FilterOperator: string
{
    case EQUALS = 'eq';
    case NOT_EQUALS = 'ne';
    case GREATER_THAN = 'gt';
    case GREATER_THAN_EQUALS = 'ge';
    case LESS_THAN = 'lt';
    case LESS_THAN_EQUALS = 'le';
    case IN = 'in';
}
