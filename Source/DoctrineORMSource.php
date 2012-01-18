<?php

namespace NetTeam\System\DataTableBundle\Source;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use DoctrineExtensions\Paginate\Paginate;
use Doctrine\ORM\Query\Lexer;
use NetTeam\System\CoreBundle\Util\String;

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

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    private function setResultCallbacks($results){
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
        $results = $this->queryBuilder->getQuery()->setFirstResult($offset)->setMaxResults($limit)->getResult();
        return $this->setResultCallbacks($results);
    }
    
    public function getDataAll(){
        $results = $this->queryBuilder->getQuery()->getResult();
        return $this->setResultCallbacks($results);
    }

    public function globalSearch(array $keys, $search)
    {
        $conditions = $this->queryBuilder->expr()->orx();
        foreach ($keys as $key) {
            $conditions->add($this->queryBuilder->expr()->like($this->queryBuilder->expr()->upper($key), "?1"));
        }
        $this->queryBuilder
                ->andWhere($conditions)
                ->setParameter(1, String::toUpper("%$search%"));
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
        return Paginate::getTotalQueryResults($this->queryBuilder->getQuery());
    }
}