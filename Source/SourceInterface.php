<?php
namespace NetTeam\System\DataTableBundle\Source;

interface SourceInterface extends \Countable
{
    public function getData($offset, $limit);

    public function addSorting($column, $order);

    /**
     * Wyszukuje po zadanych kolumnach
     *
     * @param array $keys
     * @param unknown_type $search
     */
    public function globalSearch(array $keys, $search);
}