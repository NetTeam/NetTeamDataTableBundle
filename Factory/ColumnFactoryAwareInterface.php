<?php

namespace NetTeam\Bundle\DataTableBundle\Factory;

use NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory;

/**
 * Interface dla klas posiadających ColumnFactory
 */
interface ColumnFactoryAwareInterface
{
    /**
     * Setter dla ColumnFactory
     *
     * @param \NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory $columnFactory
     */
    public function setColumnFactory(ColumnFactory $columnFactory);
}
