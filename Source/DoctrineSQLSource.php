<?php

namespace NetTeam\System\DataTableBundle\Source;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use DoctrineExtensions\Paginate\Paginate;
use Doctrine\ORM\Query\Lexer;
use NetTeam\System\CoreBundle\Util\String;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\NativeQuery;
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
    
    public function __construct(NativeQuery $query)
    {
        $this->query = $query;
        //$this->count = Paginate::getTotalQueryResults($this->query);
        $this->query->getSQL();
        $em  = $this->query->getEntityManager();
        
        $rsm = new ResultSetMapping;        
        $rsm->addScalarResult('count', 'count');
        
        $sql = "SELECT count(*) as count FROM (".$this->query->getSQL().") as foo";
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameters($this->query->getParameters());
        $this->count = $query->getSingleScalarResult();
    }

    public function replaceFields($find, $replace){
        
        
    }
    
    public function getData($offset, $limit)
    {
        $sql = $this->query->getSQL();
        $sql .= " LIMIT " . (int) $limit . " OFFSET ". (int) $offset;
        $this->query->setSQL($sql);
        
        $results = $this->query->getResult();
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