<?php

declare(strict_types=1);

namespace GlobyApp;

use GlobyApp\OdataQueryParser\OdataQuery;
use InvalidArgumentException;

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
     * OdataQueryParser::parse("http://example.com?$select=[field]", true)
     * ```
     *
     * @param string $url The URL to parse the query strings from. It should be a "complete" or "full" URL
     * @param bool $withDollar When set to false, parses the odata keys without requiring the $ in front of odata keys
     *
     * @return OdataQuery|null OdataQuery object, parsed version of the input url, or null, if there is no query string
     * @throws InvalidArgumentException The URL is malformed and could not be processed
     */
    public static function parse(string $url, bool $withDollar = true): ?OdataQuery
    {
        // Verify the URL is valid
        if (\filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('Url should be a valid, full URL.');
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
        if (self::selectQueryParameterIsValid($parsedQueryString)) {
            $output["select"] = static::getSelectColumns();
        }

        if (static::countQueryParameterIsValid($parsedQueryString)) {
            $output["count"] = true;
        }

        if (static::topQueryParameterIsValid($parsedQueryString)) {
            $top = static::getTopValue();

            if (!\is_numeric($top)) {
                throw new InvalidArgumentException('top should be an integer');
            }

            $top = $top;

            if ($top < 0) {
                throw new InvalidArgumentException('top should be greater or equal to zero');
            }

            $output["top"] = (int) $top;
        }

        if (static::skipQueryParameterIsValid($parsedQueryString)) {
            $skip = static::getSkipValue();

            if (!\is_numeric($skip)) {
                throw new InvalidArgumentException('skip should be an integer');
            }

            $skip = $skip;

            if ($skip < 0) {
                throw new InvalidArgumentException('skip should be greater or equal to zero');
            }

            $output["skip"] = (int) $skip;
        }

        if (static::orderByQueryParameterIsValid($parsedQueryString)) {
            $items = static::getOrderByColumnsAndDirections();

            $orderBy = \array_map(function ($item) {
                $explodedItem = \explode(" ", $item);

                $explodedItem = array_values(array_filter($explodedItem, function ($item) {
                    return $item !== "";
                }));

                $property = $explodedItem[0];
                $direction = isset($explodedItem[1]) ? $explodedItem[1] : "asc";

                if ($direction !== "asc" && $direction !== "desc") {
                    throw new InvalidArgumentException('direction should be either asc or desc');
                }

                return [
                    "property" => $property,
                    "direction" => $direction
                ];
            }, $items);

            $output["orderBy"] = $orderBy;
        }

        if (static::filterQueryParameterIsValid($parsedQueryString)) {
            $ands = static::getFilterValue();

            $output["filter"] = $ands;
        }


        return $output;
    }

    /**
     * Function to extract the query string from the input URL
     *
     * @param string $url The URL to parse
     *
     * @return string|null The query string from the input URL. Null if there is no query string.
     * @throws InvalidArgumentException The URL is malformed and the query string could not be extracted
     */
    private static function extractQueryString(string $url): ?string
    {
        $queryString = parse_url($url, PHP_URL_QUERY);

        if ($queryString === false) {
            throw new InvalidArgumentException("URL could not be parsed. Ensure the URL is not malformed.");
        }

        // The URL query string parser should return a string or null query string
        if (!($queryString === null || is_string($queryString))) {
            throw new InvalidArgumentException("URL query string should be a string.");
        }

        return $queryString;
    }

    /**
     * Function to parse the query string into it's separate components
     *
     * @param string $queryString The query string to parse
     *
     * @return array<array-key, mixed> The components of the query string, split up into an array
     */
    private static function parseQueryString(string $queryString): array
    {
        $result = [];
        parse_str($queryString, $result);

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
    private static function buildKeyConstant(string $key, bool $withDollar): string
    {
        return $withDollar ? '$'.$key : $key;
    }

    /**
     * Function to determine whether a odata key is present in the input query string
     *
     * @param string $key The key to check for
     * @param array<array-key, mixed> $queryString The query string in which to find the key
     *
     * @return bool Whether the odata key is present in the input query string
     */
    private static function hasKey(string $key, array $queryString): bool
    {
        return array_key_exists($key, $queryString);
    }

    /**
     * Function to determine whether a select clause is present and valid in a query string
     *
     * @param array<array-key, mixed> $queryString The query string to find the select key in
     *
     * @return bool Whether the select key exists in the query string and is valid
     */
    private static function selectQueryParameterIsValid(array $queryString): bool
    {
        return self::hasKey(self::$select, $queryString)
            && !empty($queryString[self::$select]);
    }

    /**
     * Function to determine whether a count key is present and valid in a query string
     *
     * @param array<array-key, mixed> $queryString The query string to find the count key in
     *
     * @return bool Whether the count key exists in the query string and is valid
     */
    private static function countQueryParameterIsValid(array $queryString): bool
    {
        return self::validateWithFilterValidate($queryString, self::$count, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Function to determine whether a top key is present and valid in a query string
     *
     * @param array<array-key, mixed> $queryString The query string to find the top key in
     *
     * @return bool Whether the top key exists in the query string and is valid
     */
    private static function topQueryParameterIsValid(array $queryString): bool
    {
        return self::validateWithFilterValidate($queryString, self::$top, FILTER_VALIDATE_INT);
    }

    /**
     * Function to determine whether a skip key is present and valid in a query string
     *
     * @param array<array-key, mixed> $queryString The query string to find the skip key in
     *
     * @return bool Whether the skip key exists in the query string and is valid
     */
    private static function skipQueryParameterIsValid(array $queryString): bool
    {
        return self::validateWithFilterValidate($queryString, self::$skip, FILTER_VALIDATE_INT);
    }

    /**
     * Function to determine whether a order by clause is present and valid in a query string
     *
     * @param array<array-key, mixed> $queryString The query string to find the order by key in
     *
     * @return bool Whether the order by key exists in the query string and is valid
     */
    private static function orderByQueryParameterIsValid(array $queryString): bool
    {
        return self::hasKey(self::$orderBy, $queryString)
            && !empty($queryString[self::$orderBy]);
    }

    /**
     * Function to determine whether a filter clause is present and valid in a query string
     *
     * @param array<array-key, mixed> $queryString The query string to find the filter key in
     *
     * @return bool Whether the filter key exists in the query string and is valid
     */
    private static function filterQueryParameterIsValid(array $queryString): bool
    {
        return self::hasKey(self::$filter, $queryString)
            && !empty($queryString[self::$filter]);
    }

    /**
     * Function to easily validate that an array key exists in a query string and adheres to a specified filter_var filter
     *
     * @param array<array-key, mixed> $queryString The query string to validate
     * @param string $key The key to check in the query string
     * @param int $filter The filter to validate the value against, if it exists in the query string
     *
     * @return bool Whether the key exists in the query string and adheres to the specified filter
     */
    private static function validateWithFilterValidate(array $queryString, string $key, int $filter): bool
    {
        if (!self::hasKey($key, $queryString)) {
            return false;
        }

        // Trim can only be used on a string and count. At this point, the value has not been cast to a native datatype
        if (!is_string($queryString[$key]) || empty(trim($queryString[$key]))) {
            return false;
        }

        // Verify the value adheres to the specified filter
        return filter_var($queryString[$key], $filter, FILTER_NULL_ON_FAILURE) !== null;
    }

    private static function getSelectColumns(): array
    {
        return array_map(function ($column) {
            return trim($column);
        }, explode(",", static::$queryStrings[static::$selectKey]));
    }

    private static function getTopValue(): string
    {
        return trim(static::$queryStrings[static::$topKey]);
    }

    private static function getSkipValue(): string
    {
        return trim(static::$queryStrings[static::$skipKey]);
    }

    private static function getOrderByColumnsAndDirections(): array
    {
        return explode(",", static::$queryStrings[static::$orderByKey]);
    }

    private static function getFilterValue(): array
    {
        return array_map(function ($and) {
            $items = [];

            preg_match("/(\w+)\s*(eq|ne|gt|ge|lt|le|in)\s*([\w',()\s.]+)/", $and, $items);

            $left = $items[1];
            $operator = static::getFilterOperatorName($items[2]);
            $right = static::getFilterRightValue($operator, $items[3]);

            /**
             * @todo check whether [1], [2] and [3] are set -> will fix in a different PR
             */

            return [
                "left" => $left,
                "operator" => $operator,
                "right" => $right
            ];
        }, explode("and", static::$queryStrings[static::$filterKey]));
    }

    private static function getFilterOperatorName(string $operator): string
    {
        return match ($operator) {
            "eq" => "equal",
            "ne" => "notEqual",
            "gt" => "greaterThan",
            "ge" => "greaterOrEqual",
            "lt" => "lowerThan",
            "le" => "lowerOrEqual",
            "in" => "in",
            default => "unknown",
        };
    }

    private static function getFilterRightValue(string $operator, string $value): int|float|string|array
    {
        if ($operator !== "in") {
            if (is_numeric($value)) {
                if ((int) $value != $value) {
                    return (float) $value;
                } else {
                    return (int) $value;
                }
            } else {
                return str_replace("'", "", trim($value));
            }
        } else {
            $value = preg_replace("/^\s*\(|\)\s*$/", "", $value);
            $values = explode(",", $value);

            return array_map(function ($value) {
                return static::getFilterRightValue("equal", $value);
            }, $values);
        }
    }
}
