# odata-query-parser

Parse OData v4 query strings, outputs proper PHP objects.

[![Packagist Version](https://img.shields.io/packagist/v/globyapp/odata-query-parser)](https://packagist.org/packages/globyapp/odata-query-parser) [![Packagist](https://img.shields.io/packagist/l/globyapp/odata-query-parser)](https://github.com/Globy-App/odata-query-parser/blob/master/LICENSE) [![PHP from Packagist](https://img.shields.io/packagist/php-v/globyapp/odata-query-parser)](https://github.com/globyapp/odata-query-parser/blob/master/composer.json#L14) [![CI](https://github.com/Globy-App/odata-query-parser/actions/workflows/ci.yml/badge.svg)](https://github.com/Globy-App/odata-query-parser/actions/workflows/ci.yml)

## Summary

- [About](#about)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Examples](#examples)
- [API](#api)
- [Known issues](#known-issues)

## About

I needed to only parse query strings to convert OData v4 commands into an understandable array that I could use to make a Laravel package to offer a way to automatically use Eloquent to filter the response according to this parsed array of OData v4 command.

As I did not see a package exclusively dealing with parsing the query strings, and saw that [some people worked on their own without open sourcing it](https://stackoverflow.com/questions/14145604/parse-odata-query-uri-into-php-array), I decided I would start one myself.

## Features

- Parses an URL and returns an array
- Supports `$select`, `$top`, `$skip`, `$orderby`, `$count`
- Partial support for `$filter` (see [Known issues](#known-issues) section)
- You can use a parse mode that let you parse these keywords without prepending `$`

## Requirements

- PHP >= 8.1.0
- [Composer](https://getcomposer.org/)

## Installation

Add the package to your dependencies:

```bash
composer require globyapp/odata-query-parser
```

## Examples

- [1. Use \$select to filter on some fields](#1-use-select-to-filter-on-some-fields)
- [2. Use non dollar syntax](#2-use-non-dollar-syntax)

### 1. Use \$select to filter on some fields

In this example, we will use the `$select` OData query string command to filter the fields returned by our API.

```php
use GlobyApp\OdataQueryParser;

$data = OdataQueryParser::parse('https://example.com/api/user?$select=id,name,age');
```

If you inspect `$data`, this is what you will get:

```php
[
  "select" => [
    "id",
    "name",
    "age"
  ]
]
```

### 2. Use non dollar syntax

In this example, we will use a unique feature of this library: to be able to not specify any dollar, while still being able to use the OData v4 URL query parameter grammar.

```php
use GlobyApp\OdataQueryParser;

$data = OdataQueryParser::parse("https://example.com/api/user?select=id,name,age", $withDollar = false);
```

If you inspect `$data`, this is what you will get:

```php
[
  "select" => [
    "id",
    "name",
    "age"
  ]
]
```

## API

```php
OdataQueryParser::parse(string $url, bool $withDollar = true): array;
```

**parameters**

- string `$url`: The URL to parse the query strings from. It should be a "complete" or "full" URL, which means that `https://example.com` will pass while `example.com` will not pass
- bool `$withDollar`: Set it to false if you want to parse query strings without having to add the `$` signs before each key.

**returns**

An associative array:

```php
return = [
	string? "select" => array<string>,
	string? "count" => bool,
	string? "top" => int,
	string? "skip" => int,
	string? "orderBy" => array<OrderBy>,
	string? "filter" => array<Filter>
];

OrderBy = [
	string "property" => string,
	string "direction" => Direction
]

Direction = "asc" | "desc"

Filter = [
	string "left" => string,
	string "operator" => string,
	string "right" => mixed
]
```

**throws**

- `InvalidArgumentException`
  - If the parameter `$url` is not a valid URL (see the parameter description to know what is a valid URL)
  - If the `$top` query string value is not an integer
  - If the `$top` query string value is lower than 0
  - If the `$skip` query string value is not an integer
  - If the `$skip` query string value is lower than 0
  - If the direction of the `$orderby` query string value is neither `asc` or `desc`

## Known issues

- `$filter` command will not parse `or` and functions (like `contains()` of `substringof`), because I did not focus on this for the moment (the parser for `$filter` is too simplistic, I should find a way to create an AST).
