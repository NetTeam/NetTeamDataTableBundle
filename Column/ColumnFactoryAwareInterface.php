<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

use NetTeam\Bundle\DataTableBundle\Column\ColumnFactory;

/**
 * Interface dla klas posiadających ColumnFactory
 */
interface ColumnFactoryAwareInterface
{
    /**
     * Setter dla ColumnFactory
     *
     * @param \NetTeam\Bundle\DataTableBundle\Column\ColumnFactory $columnFactory
     */
    public function setColumnFactory(ColumnFactory $columnFactory);
}
