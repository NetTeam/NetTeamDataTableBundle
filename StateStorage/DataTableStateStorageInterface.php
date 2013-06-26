<?php

namespace NetTeam\Bundle\DataTableBundle\StateStorage;

use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;
/**
 * Interface to store datatable state
 */
interface DataTableStateStorageInterface
{

    /**
     * Gets saved datatable state
     *
     * @param  \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder $datatableBuilder
     * @return array
     */
    public function get(DataTableBuilder $datatableBuilder);

    /**
     * Stores datatable state
     * @param \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder $datatableBuilder
     * @param array                                                      $parameters
     */
    public function set(DataTableBuilder $datatableBuilder, array $parameters);

    /**
     * Checks if state for datatable exists
     * @param  \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder $datatableBuilder
     * @return boolean
     */
    public function has(DataTableBuilder $datatableBuilder);

}
