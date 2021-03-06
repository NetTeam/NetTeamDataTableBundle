<?php

namespace NetTeam\Bundle\DataTableBundle\Source;

use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Query\Query;

/**
 * Źródło danych dla DataTable - Builder Doctrine ODM
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class MongoBuilderSource implements SourceInterface
{
    protected $builder;

    public function __construct(Builder $builder)
    {
        if ($builder->getType() !== Query::TYPE_FIND) {
            throw new \UnexpectedValueException('ODM query must be a FIND type query');
        }

        $this->builder = $builder;
    }

    public function getData($offset, $limit)
    {
        $this->builder->skip($offset)->limit($limit);
        $cursor = $this->builder->getQuery()->execute();

        return $cursor->toArray();
    }

    public function getDataAll()
    {
        throw new  RuntimeException('Not yet implemented');
    }

    public function globalSearch(array $keys, $search)
    {
        foreach ($keys as $key) {
            $this->builder->addOr($this->builder->expr()->field($key)->equals(new \MongoRegex(sprintf('/%s/i', $search))));
        }
    }

    public function addSorting($column, $order)
    {
        $this->builder->sort($column, $order);
    }

    public function count()
    {
        $builder = clone $this->builder;
        $builder->skip(null)->limit(null);

        return $builder->getQuery()->count(true);
    }

    public function getBuilder()
    {
        return $this->builder;
    }
}
