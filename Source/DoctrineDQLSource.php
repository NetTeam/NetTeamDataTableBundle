<?php

namespace NetTeam\System\DataTableBundle\Source;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use DoctrineExtensions\Paginate\Paginate;
use Doctrine\ORM\Query\Lexer;
use NetTeam\System\CoreBundle\Util\String;

/**
 * Źródło danych dla DataTable - Query Doctrine ORM
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 */
class DoctrineDQLSource implements SourceInterface
{
    /**
     * @var Query
     */
    private $query;
    private $count;
    
    public function __construct(Query $query)
    {
        $this->query = $query;
        $this->count = Paginate::getTotalQueryResults($this->query);
    }

    public function getData($offset, $limit)
    {
        $results = $this->query->setFirstResult($offset)->setMaxResults($limit)->getArrayResult();

        return $results;
    }
    
    public function getDataAll()
    {
        $results = $this->query->getArrayResult();
        return $results;
    }

    public function globalSearch(array $keys, $search)
    {
        return null;
    }

    public function addSorting($column, $order)
    {
        return null;
    }

    public function count()
    {
        return $this->count;
    }
}