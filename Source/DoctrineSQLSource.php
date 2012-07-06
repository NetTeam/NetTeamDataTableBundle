<?php

namespace NetTeam\Bundle\DataTableBundle\Source;

use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Źródło danych dla DataTable - NativeQuery Doctrine ORM
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 */
class DoctrineSQLSource implements SourceInterface
{

    /**
     * @var NativeQuery
     */
    private $query;
    private $querySQL;
    private $count;
    private $sorting = array();
    protected $rowCallback;
    protected $dataCallback;

    public function __construct(NativeQuery $query)
    {
        $this->query = $query;
        $this->querySQL = $this->query->getSQL();
        $em = $this->query->getEntityManager();

        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('count', 'count');

        $sql = "SELECT count(*) as count FROM (" . $this->querySQL . ") as foo";
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameters($this->query->getParameters());
        $this->count = $query->getSingleScalarResult();
    }

    public function replaceFields($find, $replace)
    {

    }

    public function getData($offset, $limit)
    {
        $results = $this->getResult($offset, $limit);

        if (null !== $callback = $this->dataCallback) {
            $results = $callback($results);
        }

        if ($this->rowCallback !== null) {
            $results = array_map($this->rowCallback, $results);
        }

        return $results;
    }

    public function getDataAll()
    {
        $results = $this->getResult();

        if (null !== $callback = $this->dataCallback) {
            $results = $callback($results);
        }

        if ($this->rowCallback !== null) {
            $results = array_map($this->rowCallback, $results);
        }

        return $results;
    }

    public function globalSearch(array $keys, $search)
    {
        return null;
    }

    public function addSorting($column, $order)
    {
        $this->sorting[] = sprintf("%s %s", $column, $order);
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
        return $this->count;
    }

    protected function getResult($offset = null, $limit = null)
    {
        $sql = $this->querySQL;
        if (count($this->sorting)) {
            $sql .= " ORDER BY " . implode($this->sorting, ', ');
        }

        if (null !== $limit && null !== $offset) {
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
        } elseif (null !== $limit) {
            $sql .= " LIMIT " . (int) $limit;
        }

        $this->query->setSQL($sql);
        return $this->query->getResult();
    }

}
