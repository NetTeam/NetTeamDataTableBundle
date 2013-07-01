<?php
namespace NetTeam\Bundle\DataTableBundle\Source;

interface SourceInterface extends \Countable
{
    /**
     *
     * @param  integer $offset
     * @param  integer $limit
     * @return array
     */
    public function getData($offset, $limit);

    /**
     * @return array
     */
    public function getDataAll();

    /**
     * @param string $column
     * @param string $order
     */
    public function addSorting($column, $order);

    /**
     * Wyszukuje po zadanych kolumnach
     *
     * @param array        $keys
     * @param unknown_type $search
     */
    public function globalSearch(array $keys, $search);
}
