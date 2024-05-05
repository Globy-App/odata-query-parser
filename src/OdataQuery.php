<?php

namespace GlobyApp\OdataQueryParser;

use GlobyApp\OdataQueryParser\Datatype\FilterClause;
use GlobyApp\OdataQueryParser\Datatype\OrderByClause;

/**
 * @api Public get methods exposed to retrieve data from the result
 */
class OdataQuery
{
    /**
     * @var string[] $select
     */
    private array $select;

    private ?bool $count;

    private ?int $top;

    private ?int $skip;

    /**
     * @var OrderByClause[] $orderBy
     */
    private array $orderBy;

    /**
     * @var FilterClause[] $filter
     */
    private array $filter;

    /**
     * The parsed version of the input odata query string
     *
     * @param string[] $select A list of properties that should be returned
     * @param bool|null $count Whether the amount of results should be returned
     * @param int|null $top Return the top X amount of results
     * @param int|null $skip Skip the first Y results
     * @param OrderByClause[] $orderBy The list of order by clauses requested
     * @param FilterClause[] $filter The list of filter clauses requested
     */
    public function __construct(array $select = [], ?bool $count = null, ?int $top = null, ?int $skip = null, array $orderBy = [], array $filter = [])
    {
        $this->select = $select;
        $this->count = $count;
        $this->top = $top;
        $this->skip = $skip;
        $this->orderBy = $orderBy;
        $this->filter = $filter;
    }

    /**
     * @return string[] The list of properties to be returned
     */
    public function getSelect(): array
    {
        return $this->select;
    }

    /**
     * @return bool|null Whether the amount of results should be included in the request
     */
    public function isCount(): ?bool
    {
        return $this->count;
    }

    /**
     * @return int|null The top amount of results to return
     */
    public function getTop(): ?int
    {
        return $this->top;
    }

    /**
     * @return int|null The amount of results to skip before starting to return results
     */
    public function getSkip(): ?int
    {
        return $this->skip;
    }

    /**
     * @return OrderByClause[] The list of order by clauses
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @return FilterClause[] The list of filter clauses
     */
    public function getFilter(): array
    {
        return $this->filter;
    }
}
