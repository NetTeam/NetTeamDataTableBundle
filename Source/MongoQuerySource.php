<?php

namespace NetTeam\System\DataTableBundle\Source;

use Doctrine\ODM\MongoDB\Query\Query;

/**
 * Źródło danych dla DataTable - Query Doctrine ODM
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class MongoQuerySource implements SourceInterface
{
    protected $query;

    public function __construct(Query $query)
    {
        if ($query->getType() !== Query::TYPE_FIND) {
            throw new \UnexpectedValueException('ODM query must be a FIND type query');
        }

        $this->query = $query;
    }

    public function getData($offset, $limit)
    {
        $reflectionClass = new \ReflectionClass('Doctrine\MongoDB\Query\Query');
        $reflectionProperty = $reflectionClass->getProperty('query');
        $reflectionProperty->setAccessible(true);

        $queryOptions = $reflectionProperty->getValue($this->query);
        $queryOptions['limit'] = $limit;
        $queryOptions['skip'] = $offset;

        $reflectionProperty->setValue($this->query, $queryOptions);
        $cursor = $this->query->execute();

        return $cursor->toArray();
    }
    
    public function getDataAll()
    {
        throw new  RuntimeException('Not yet implemented');
    }

    public function globalSearch(array $keys, $search)
    {
        $reflectionClass = new \ReflectionClass('Doctrine\MongoDB\Query\Query');
        $reflectionProperty = $reflectionClass->getProperty('query');
        $reflectionProperty->setAccessible(true);

        $queryOptions = $reflectionProperty->getValue($this->query);

        if (!isset($queryOptions['query']['$or'])) {
            $queryOptions['query']['$or'] = array();
        }

        foreach ($keys as $key) {
            $queryOptions['query']['$or'][] = array($key => new \MongoRegex(sprintf('/%s/i', $search)));
        }

        $reflectionProperty->setValue($this->query, $queryOptions);
    }

    public function addSorting($column, $order)
    {
        $reflectionClass = new \ReflectionClass('Doctrine\MongoDB\Query\Query');
        $reflectionProperty = $reflectionClass->getProperty('query');
        $reflectionProperty->setAccessible(true);

        $queryOptions = $reflectionProperty->getValue($this->query);
        $queryOptions['sort'] = array($column => $order);

        $reflectionProperty->setValue($this->query, $queryOptions);
    }

    public function count()
    {
        return $this->query->count(true);
    }
}