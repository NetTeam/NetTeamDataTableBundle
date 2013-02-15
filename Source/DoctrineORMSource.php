<?php

namespace NetTeam\Bundle\DataTableBundle\Source;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator;
use NetTeam\Bundle\DataTableBundle\Util\Doctrine\Cast;
use NetTeam\Bundle\DataTableBundle\Util\String;

/**
 * Źródło danych dla DataTable - QueryBuilder Doctrine ORM
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class DoctrineORMSource implements SourceInterface
{

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;
    protected $sortingCallbacks = array();
    protected $rowCallback;
    protected $dataCallback;
    protected $hydrationMode;

    public function __construct(QueryBuilder $queryBuilder, $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        $this->queryBuilder = $queryBuilder;
        $this->hydrationMode = $hydrationMode;
    }

    private function setResultCallbacks(array $results)
    {
        if (null !== $callback = $this->dataCallback) {
            $results = $callback($results);
        }

        if ($this->rowCallback !== null) {
            $results = array_map($this->rowCallback, $results);
        }

        return $results;
    }

    public function getData($offset, $limit)
    {
        $query = $this->queryBuilder->getQuery();
        $query->setHydrationMode($this->hydrationMode);
        $query->setFirstResult($offset)->setMaxResults($limit);

        $results = iterator_to_array($this->getPaginator($query));

        return $this->setResultCallbacks($results);
    }

    public function getDataAll()
    {
        $results = $this->queryBuilder->getQuery()->getResult($this->hydrationMode);

        return $this->setResultCallbacks($results);
    }

    public function globalSearch(array $keys, $search)
    {
        $conditions = $this->queryBuilder->expr()->orx();
        foreach ($keys as $key) {
            $conditions->add($this->queryBuilder->expr()->like($this->queryBuilder->expr()->upper(new Cast($key, 'text')), ":search"));
        }
        $this->queryBuilder
                ->andWhere($conditions)
                ->setParameter('search', String::toUpper("%$search%"));
    }

    public function addSorting($column, $order)
    {
        if (isset($this->sortingCallbacks[$column])) {
            $this->sortingCallbacks[$column]($this->queryBuilder, $order);
        } else {
            $this->queryBuilder->addOrderBy($column, $order);
        }
    }

    public function setColumnSorting($column, \Closure $callback)
    {
        $this->sortingCallbacks[$column] = $callback;
    }

    public function setRowCallback(\Closure $callback)
    {
        $this->rowCallback = $callback;
    }

    public function setDataCallback(\Closure $callback)
    {
        $this->dataCallback = $callback;
    }

    public function count()
    {
        return $this->getPaginator($this->queryBuilder)->count();
    }

    /**
     * Clones a query.
     *
     * @param Query $query The query.
     *
     * @return Query The cloned query.
     */
    private function cloneQuery(Query $query)
    {
        /* @var $cloneQuery Query */
        $cloneQuery = clone $query;
        $cloneQuery->setParameters($query->getParameters());
        foreach ($query->getHints() as $name => $value) {
            $cloneQuery->setHint($name, $value);
        }

        return $cloneQuery;
    }

    public function getBuilder()
    {
        return $this->queryBuilder;
    }

    protected function getPaginator($query)
    {
        return new Paginator($query);
    }

}
