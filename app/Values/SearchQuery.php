<?php

namespace App\Values;

/**
 * Class SearchQuery
 * @package App\Values
 */
class SearchQuery {
    const COLUMN = 'column';
    const OPERATOR = 'operator';
    const VALUE = 'value';

    protected $queries = [];

    /**
     * @return string
     */
    public function columnKey()
    {
        return self::COLUMN;
    }

    /**
     * @return string
     */
    public function operatorKey()
    {
        return self::OPERATOR;
    }

    /**
     * @return string
     */
    public function valueKey()
    {
        return self::VALUE;
    }

    /**
     * @param $column
     * @param $operator
     * @param $value
     */
    public function setQuery($column, $operator, $value)
    {
        $this->queries[] = [
            $this->columnKey() => $column,
            $this->operatorKey() => $operator,
            $this->valueKey() => $value
        ];
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }
}