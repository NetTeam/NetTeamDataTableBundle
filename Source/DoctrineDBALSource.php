<?php

namespace NetTeam\Bundle\DataTableBundle\Source;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;

/**
 * Źródło danych dla DataTable - Zapytanie SQL
 *
 * W treści zapytania można dodatkowo korzystać z tagów:
 * {sort} - zamieniany później na statement sortowania
 * {search} - zamieniany na warunki wyszukiwania globalnego
 * {limit_offset} - zamieniany na LIMIT i OFFSET potrzebne do sortowania
 *
 * Przykładowo:
 * SELECT *
 * FROM users
 * WHERE {search}
 * ORDER BY {sort}
 * {limit_offset}
 *
 * Zamieni się na:
 * SELECT *
 * FROM users
 * WHERE UPPER(name) LIKE '%' || UPPER(:search) || '%s' OR UPPER(surname) LIKE '%' || UPPER(:search) || '%s'
 * ORDER BY name ASC
 * LIMIT 25 OFFSET 0
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class DoctrineDBALSource implements SourceInterface
{
    private $connection;
    private $query;
    private $countQuery;

    private $searchKeys = array();
    private $searchString;

    private $sortingColumn;
    private $sortingOrder;

    private $bindedValues = array();

    public function __construct(Connection $connection, $query = null, $countQuery = null)
    {
        $this->connection = $connection;
        $this->query = $query;
        $this->countQuery = $countQuery;
    }

    public function getData($offset, $limit)
    {
        $query = $this->query;

        /**
         * Przygotowujemy zapytanie -- zamieniamy tagi:
         * {sort} -- statement sortowania
         * {search} -- warunki global search-a
         */
        $query = $this->replaceTag('sort', "$this->sortingColumn $this->sortingOrder", $query);
        $query = $this->replaceTag('search', $this->prepareSearchStatement(), $query);

        $query = $this->preparePagination($query);

        $statement = $this->connection->prepare($query);

        foreach ($this->bindedValues as $name => $value) {
            $statement->bindValue($name, $value);
        }
        $this->bindStatementValue('search', $this->searchString, $statement);
        $this->bindStatementValue('limit', $limit, $statement);
        $this->bindStatementValue('offset', $offset, $statement);

        $statement->execute();
        return $statement->fetchAll();
    }
    
    public function getDataAll()
    {
        throw new  RuntimeException('Not yet implemented');
    }
    

    public function globalSearch(array $keys, $search)
    {
        $this->searchString = $search;
        $this->searchKeys = $keys;
    }

    public function addSorting($column, $order)
    {
        $this->sortingColumn = $column;
        $this->sortingOrder = $order;
    }

    public function count()
    {
        return $this->connection->fetchColumn($this->countQuery, array(), 0);
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function setCountQuery($countQuery)
    {
        $this->countQuery = $countQuery;
    }

    public function bindValue($name, $value)
    {
        $this->bindedValues[$name] = $value;
    }

    /**
     * Zamienia tag {nazwa} na argument $replace
     *
     * @param string $name
     * @param string $replace
     * @param string $query
     */
    private function replaceTag($name, $replace, $query)
    {
        $pattern = sprintf('/{\s*%s\s*}/', $name);
        return preg_replace($pattern, $replace, $query);
    }

    private function bindStatementValue($name, $value, Statement $statement)
    {
        if (strpos($statement->getWrappedStatement()->queryString, ':'.$name) !== false) {
            $statement->bindValue($name, $value);
        }
    }

    private function prepareSearchStatement()
    {
        $searchStatement = implode(' OR ', array_map(function ($key) {
            return sprintf("UPPER(%s) LIKE '%%' || UPPER(:search) || '%%'", $key);
        }, $this->searchKeys));

        return $searchStatement ?: '1 = 1';
    }

    /**
     * Zamienia tag {limit_offset} na LIMIT i OFFSET
     * lub jeśli nie ma dokleja statement na końcu zapytania
     *
     * @param string $query
     */
    private function preparePagination($query)
    {
        $query = $this->replaceTag('limit_offset', "LIMIT :limit OFFSET :offset", $query);
        if (strpos($query, ':limit') === false) {
            $query .= ' LIMIT :limit OFFSET :offset';
        }

        return $query;
    }
}