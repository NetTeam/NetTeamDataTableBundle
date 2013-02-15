<?php

namespace NetTeam\Bundle\DataTableBundle\Source;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    public function __construct(Query $query)
    {
        $this->query = $query;
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
        $paginator = new Paginator($this->query);

        return $paginator->count();
    }
}
