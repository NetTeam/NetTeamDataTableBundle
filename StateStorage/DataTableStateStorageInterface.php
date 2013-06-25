<?php

namespace NetTeam\Bundle\DataTableBundle\StateStorage;

use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;

interface DataTableStateStorageInterface
{

    /**
     * Gets saved datatable query
     *
     * @param  \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder $datatableBuilder
     * @return array
     */
    public function get(DataTableBuilder $datatableBuilder);

    /**
     *
     * @param \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder $datatableBuilder
     * @param array                                                      $parameters
     */
    public function set(DataTableBuilder $datatableBuilder, array $parameters);

    /**
     * @param  \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder $datatableBuilder
     * @return boolean
     */
    public function has(DataTableBuilder $datatableBuilder);

}
