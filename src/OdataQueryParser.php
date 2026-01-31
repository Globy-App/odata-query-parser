<?php

declare(strict_types=1);

namespace GlobyApp\OdataQueryParser;

use GlobyApp\OdataQueryParser\Datatype\FilterClause;
use GlobyApp\OdataQueryParser\Datatype\OrderByClause;
use GlobyApp\OdataQueryParser\Enum\FilterOperator;
use GlobyApp\OdataQueryParser\Enum\OrderDirection;
use InvalidArgumentException;
use LogicException;

/**
 * The actual parser class that can parse an odata url
 * @api
 * @package GlobyApp\OdataQueryParser
 */
class OdataQueryParser
{
    private static string $select;
    private static string $count;
    private static string $filter;
    private static string $orderBy;
    private static string $skip;
    private static string $top;

    /**
     * Parses a given URL, returns a result object with the odata parts of the URL.
     *
     * Usage:
     * ```
     *  OdataQueryParser::parse("http://example.com?$select=[field]&$top=10&$skip=5&$orderBy", true)
     *  OdataQueryParser::parse("http://example.com?select=[field]&top=10&skip=5&orderBy", false)
     * ```
     *
     * @param string $url The URL to parse the query strings from. It should be a "complete" or "full" URL
     * @param bool $withDollar When set to false, parses the odata keys without requiring the $ in front of odata keys
     *
     * @return OdataQuery|null OdataQuery object, parsed version of the input url, or null, if there is no query string
     * @throws InvalidArgumentException The URL, or parts of it are malformed and could not be processed
     */
    public static function parse(string $url, bool $withDollar = true): ?OdataQuery
    {
        // Verify the URL is valid
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('URL should be a valid, full URL.');
        }

        // Extract the query string from the URL and parse it into it's components
        $queryString = self::extractQueryString($url);
        if ($queryString === null) {
            // There is no query string, so there cannot be a result
            return null;
        }

        $parsedQueryString = self::parseQueryString($queryString);
        self::setKeyConstants($withDollar);

        // Extract the different odata keys and store them in the output array
        $select = self::getSelect($parsedQueryString);
        $count = self::getCount($parsedQueryString);
        $top = self::getTop($parsedQueryString);
        $skip = self::getSkip($parsedQueryString);
        $orderBy = self::getOrderBy($parsedQueryString);
        $filter = self::getFilter($parsedQueryString);

        return new OdataQuery($select, $count, $top, $skip, $orderBy, $filter);
    }

    /**
     * Function to extract the query string from the input URL
     *
     * @param string $url The URL to parse
     *
     * @return string|null The query string from the input URL. Null if there is no query string.
     * @throws InvalidArgumentException The URL is malformed and the query string could not be extracted
     */
    protected static function extractQueryString(string $url): ?string
    {
        $queryString = parse_url($url, PHP_URL_QUERY);

        if ($queryString === false) {
            throw new InvalidArgumentException("URL could not be parsed. Ensure the URL is not malformed.");
        }

        return $queryString;
    }

    /**
     * Function to parse the query string into it's separate components
     *
     * @param string $queryString The query string to parse
     *
     * @return array<string, string> The components of the query string, split up into an array
     */
    public static function parseQueryString(string $queryString): array
    {
        $result = [];
        parse_str($queryString, $result);

        // Verify that the parsed result only has string key and values
        foreach ($result as $key => $value) {
            if (!is_string($key) || !is_string($value)) {
                throw new InvalidArgumentException("Parsed query string has non-string values.");
            }
        }

        /* @phpstan-ignore-next-line The structure of the return value is verified in the foreach block above */
        return $result;
    }

    /**
     * Function to set the odata key constants depending on the $withDollar configuration
     *
     * @param bool $withDollar Whether to prepend a dollar key to the key name
     *
     * @return void Nothing, the method just sets the constants
     */
    private static function setKeyConstants(bool $withDollar): void
    {
        self::$select = self::buildKeyConstant("select", $withDollar);
        self::$count = self::buildKeyConstant("count", $withDollar);
        self::$filter = self::buildKeyConstant("filter", $withDollar);
        self::$orderBy = self::buildKeyConstant("orderby", $withDollar);
        self::$skip = self::buildKeyConstant("skip", $withDollar);
        self::$top = self::buildKeyConstant("top", $withDollar);
    }

    /**
     * Function to prepend a dollar to a key if required.
     *
     * @param string $key The name of the key to be built
     * @param bool $withDollar Whether to prepend a dollar sign
     *
     * @return string The key with or without dollar sign prepended
     */
    protected static function buildKeyConstant(string $key, bool $withDollar): string
    {
        return $withDollar ? '$'.$key : $key;
    }

    /**
     * Function to determine whether an odata key is present in the input query string
     *
     * @param string $key The key to check for
     * @param array<string, string> $queryString The query string in which to find the key
     *
     * @return bool Whether the odata key is present in the input query string
     */
    protected static function hasKey(string $key, array $queryString): bool
    {
        return array_key_exists($key, $queryString);
    }

    /**
     * Function to easily validate that an array key exists in a query string and adheres to a specified filter_var filter
     *
     * @param array<string, string> $queryString The query string to validate
     * @param string $key The key to check in the query string
     * @param int $filter The filter to validate the value against, if it exists in the query string
     *
     * @return bool Whether the key exists in the query string and adheres to the specified filter
     * @throws InvalidArgumentException If the input value doesn't pass the given filter
     */
    private static function validateWithFilterValidate(array $queryString, string $key, int $filter): bool
    {
        if (!self::hasKey($key, $queryString)) {
            return false;
        }

        // Trim can only be used on a string and count. At this point, the value has not been cast to a native datatype
        if (empty(trim($queryString[$key])) && trim($queryString[$key]) !== '0') {
            return false;
        }

        // Verify the value adheres to the specified filter
        if (filter_var($queryString[$key], $filter, FILTER_NULL_ON_FAILURE) === null) {
            throw new InvalidArgumentException("Invalid datatype for $key");
        }

        return true;
    }

    /**
     * Function to parse and process the list of select properties
     *
     * @param array<string, string> $queryString The parsed query string
     *
     * @return string[] The list of properties to be selected
     */
    private static function getSelect(array $queryString): array
    {
        // If the original query string doesn't include a select part, return an empty array
        if (!(self::hasKey(self::$select, $queryString)
            && !empty(trim($queryString[self::$select])))) {
            return [];
        }

        // Split the select string into an array, as it's just a csv string
        $csvSplit = explode(",", $queryString[self::$select]);

        return array_map(function (string $column) {
            return trim($column);
        }, $csvSplit);
    }

    /**
     * Function to determine whether a count key is present and return a parsed version of the value
     *
     * @param array<string, string> $queryString The query string to find the count key in
     *
     * @return bool|null The value of the count key, or null, if no count key is present in the query string
     * @throws InvalidArgumentException If the input value doesn't pass the given filter
     */
    private static function getCount(array $queryString): ?bool
    {
        if (!self::validateWithFilterValidate($queryString, self::$count, FILTER_VALIDATE_BOOLEAN)) {
            // 0 and 1 are also valid values for a boolean
            if (!(array_key_exists(self::$count, $queryString)
                && (trim($queryString[self::$count]) === '0' || trim($queryString[self::$count]) === '1'))) {
                return null;
            }
        }

        return boolval(trim($queryString[self::$count]));
    }

    /**
     * Function to determine whether a top key is present and return a parsed version of the value
     *
     * @param array<string, string> $queryString The query string to find the top key in
     *
     * @return int|null The value of the top key, or null, if no top key is present in the query string
     * @throws InvalidArgumentException If the input value is not a valid integer
     */
    private static function getTop(array $queryString): ?int
    {
        if (!self::validateWithFilterValidate($queryString, self::$top, FILTER_VALIDATE_INT)) {
            return null;
        }

        // Parse skip and ensure it's larger than 0, as negative values don't make sense in this context
        $top = intval(trim($queryString[self::$top]));

        if ($top < 0) {
            throw new InvalidArgumentException('Top should be greater or equal to zero');
        }

        return $top;
    }

    /**
     * Function to determine whether a skip key is present and return a parsed version of the value
     *
     * @param array<string, string> $queryString The query string to find the skip key in
     *
     * @return int|null The value of the skip key, or null, if no skip key is present in the query string
     * @throws InvalidArgumentException If the input value is not a valid integer
     */
    private static function getSkip(array $queryString): ?int
    {
        if (!self::validateWithFilterValidate($queryString, self::$skip, FILTER_VALIDATE_INT)) {
            return null;
        }

        // Parse skip and ensure it's larger than 0, as negative values don't make sense in this context
        $skip = intval(trim($queryString[self::$skip]));

        if ($skip < 0) {
            throw new InvalidArgumentException('Skip should be greater or equal to zero');
        }

        return $skip;
    }

    /**
     * Function to split the orderBy part of a query string and return a list of order by clauses
     *
     * @param array<string, string> $queryString The query string to get the order by clauses from
     *
     * @return OrderByClause[] The parsed order by clauses
     * @throws InvalidArgumentException If the direction is not asc or desc, or the clause split found a clause that was incorrectly formed
     */
    private static function getOrderBy(array $queryString): array
    {
        if (!(self::hasKey(self::$orderBy, $queryString)
            && !empty(trim($queryString[self::$orderBy])))) {
            return [];
        }

        $csvSplit = explode(",", $queryString[self::$orderBy]);

        return array_map(function (string $clause): OrderByClause {
            $splitClause = explode(' ', $clause);

            // Remove empty strings from the result
            $splitClause = array_values(array_filter($splitClause, function ($item) {
                return !empty($item);
            }));

            // Verify that the split resulted in a valid pattern
            $splitCount = count($splitClause);
            if ($splitCount < 1 || $splitCount > 2) {
                throw new InvalidArgumentException("An order by condition is invalid and resulted in a split of $splitCount terms.");
            }

            // Parse the direction and return an OrderByClause. The default order direction is ascending
            $direction = $splitCount === 2 ? self::parseDirection(trim($splitClause[1])) : OrderDirection::ASC;
            return new OrderByClause(trim($splitClause[0]), $direction);
        }, $csvSplit);
    }

    /**
     * Function to convert the string representation of an order direction to an enum
     *
     * @param string $direction The string representation of the order direction
     *
     * @return OrderDirection The parsed order direction
     * @throws InvalidArgumentException If the direction is not asc or desc
     */
    private static function parseDirection(string $direction): OrderDirection
    {
        return match (strtolower($direction)) {
            "asc" => OrderDirection::ASC,
            "desc" => OrderDirection::DESC,
            default => throw new InvalidArgumentException("Direction should be either asc or desc"),
        };
    }

    /**
     * Function to split the filter part of a query string and return a list of filter clauses
     *
     * @param array<string, string> $queryString The query string to find the filter key in
     *
     * @return FilterClause[] The parsed list of filter clauses
     * @throws InvalidArgumentException If an invalid operator is found, or the clause split found a clause that was incorrectly formed
     */
    private static function getFilter(array $queryString): array
    {
        if (!(self::hasKey(self::$filter, $queryString)
            && !empty(trim($queryString[self::$filter])))) {
            return [];
        }

        $filterParts = explode("and", $queryString[self::$filter]);

        return array_map(function (string $clause): FilterClause {
            $clauseParts = [];
            mb_ereg("([\w\W]+)(?:\s)\s*([engliENGLI][qetnQETN])\s*([\w',()\s.]+)", $clause, $clauseParts);

            /** Determine whether there are 4 array keys present in the result:
             * $clauseParts[0]: the entire input string
             * $clauseParts[1]: the left hand side (property)
             * $clauseParts[2]: the operator
             * $clauseParts[3]: the right hand side (value)
             **/
            if (count($clauseParts) !== 4) {
                throw new InvalidArgumentException("A filter clause is invalid and resulted in a split of ".count($clauseParts)." terms.");
            }

            $operator = self::parseFilterOperator($clauseParts[2]);
            $value = self::getFilterRightValue($clauseParts[3], $operator);
            return new FilterClause(trim($clauseParts[1]), $operator, $value);
        }, $filterParts);
    }


    /**
     * Function to convert the string representation of a filter operator to an enum
     *
     * @param string $operator The string representation of the filter operator
     *
     * @return FilterOperator The parsed filter operator
     * @throws InvalidArgumentException If the filter operator is not valid
     */
    private static function parseFilterOperator(string $operator): FilterOperator
    {
        return match (strtolower($operator)) {
            "eq" => FilterOperator::EQUALS,
            "ne" => FilterOperator::NOT_EQUALS,
            "gt" => FilterOperator::GREATER_THAN,
            "ge" => FilterOperator::GREATER_THAN_EQUALS,
            "lt" => FilterOperator::LESS_THAN,
            "le" => FilterOperator::LESS_THAN_EQUALS,
            "in" => FilterOperator::IN,
            default => throw new InvalidArgumentException("Filter operator should be eq, ne, gt, ge, lt, le or in."),
        };
    }

    /**
     * Function to parse the filter right value of a filter clause to the correct php datatype
     *
     * @param string $value The value to parse into an array, or it's native php datatype
     * @param FilterOperator $operator The operator, dictates whether the value is considered a list or a single value
     *
     * @return int|float|string|bool|null|array<int|float|string|bool|null> Either a native php datatype, or an array with a mix or native php datatypes
     */
    private static function getFilterRightValue(string $value, FilterOperator $operator): int|float|string|bool|null|array
    {
        if ($operator === FilterOperator::IN) {
            // Remove the start and end bracket, including possible whitespace from the list
            $value = mb_ereg_replace("^\s*\(|\)\s*$", "", $value);

            if (!is_string($value)) {
                throw new LogicException("Could not execute regex replace on filter value.");
            }

            // Split the list in values
            $values = explode(",", $value);

            // Parse the value as a single comparison value
            return array_map(function (string $value): int|float|string|bool|null {
                return self::getFilterRightValueSingle($value);
            }, $values);
        }

        // The value is not a list of values, parse the value as a single value into it's native php datatype
        return self::getFilterRightValueSingle($value);
    }

    /**
     * Function to parse the right side filter value if it's known that the value cannot be an array
     *
     * @param string $value The value to parse into it's native php datatype
     *
     * @return int|float|string|bool|null The value parsed as a native php datatype
     */
    private static function getFilterRightValueSingle(string $value): int|float|string|bool|null
    {
        // Trim the value before testing its datatype to prevent accidental mismatches
        $value = trim($value);

        // The operator is an equality operator, parse the value according to the inferred datatype
        if (is_numeric($value)) {
            if (intval($value) == $value) {
                return intval($value);
            }
            return floatval($value);
        }

        if ($value === 'true' || $value === 'false') {
            return $value === 'true';
        }

        // Either return the string with apostrophe's, or null, if the string without apostrophe's is empty
        $stringRes = mb_ereg_replace("'", "", $value);
        return empty($stringRes) ? null : $stringRes;
    }
}
