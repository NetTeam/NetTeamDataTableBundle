<?php

namespace NetTeam\Bundle\DataTableBundle\Source;

use NetTeam\Bundle\DataTableBundle\SimpleSource\SimpleSourceInterface;

/**
 * Adapter dla SimpleSourceInterface
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class SimpleSourceAdapter implements SourceInterface
{
    protected $source;

    public function __construct(SimpleSourceInterface $source)
    {
        $this->source = $source;
    }

    public function getData($offset, $limit)
    {
        return $this->source->getData();
    }

    public function getDataAll()
    {
        return $this->source->getData();
    }

    public function globalSearch(array $keys, $search)
    {
    }

    public function addSorting($column, $order)
    {
    }

    public function count()
    {
        return null;
    }
}
