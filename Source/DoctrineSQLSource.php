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
    private $count;
    private $sorting = array();

    protected $rowCallback;
    protected $dataCallback;

    /**
     * @param NativeQuery $query
     */
    public function __construct(NativeQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @return NativeQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    public function replaceFields($find, $replace)
    {

    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function globalSearch(array $keys, $search)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function addSorting($column, $order)
    {
        $sql = $this->query->getSQL();

        $this->checkIllegalParametersInSql($sql);

        $sql .= sprintf(" ORDER BY %s %s", $column, $order);

        $this->sorting[] = sprintf("%s %s", $column, $order);

        $this->query->setSQL($sql);
    }

    /**
     * @param $sql
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function checkIllegalParametersInSql($sql)
    {
        if (preg_match('/order by|limit|offset|fetch|for/i', $sql)) {
            throw new \InvalidArgumentException("Parameters ORDER BY, LIMIT, OFFSET, FETCH, FOR are not allowed in DoctrineSQLSource query");
        }
    }

    /**
     * @param callable $callback
     */
    public function setRowCallback(\Closure $callback)
    {
        $this->rowCallback = $callback;
    }

    /**
     * @param callable $callback
     */
    public function setDataCallback(\Closure $callback)
    {
        $this->dataCallback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $em = $this->query->getEntityManager();

        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('count', 'count');

        $sql = "SELECT count(*) as count FROM (" . $this->query->getSQL() . ") as foo";
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameters($this->query->getParameters());
        $this->count = $query->getSingleScalarResult();

        return $this->count;
    }

    /**
     * @param  integer $offset
     * @param  integer $limit
     * @return array
     */
    protected function getResult($offset = null, $limit = null)
    {
        $sql = $this->query->getSQL();

        $this->checkIllegalParametersInSql($sql);

        if (null !== $limit && null !== $offset) {
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
        } elseif (null !== $limit) {
            $sql .= " LIMIT " . (int) $limit;
        }

        $this->query->setSQL($sql);

        return $this->query->getResult();
    }

}
