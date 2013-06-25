<?php

namespace NetTeam\Bundle\DataTableBundle\Event;

use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event wywoływany w DataTableController przy pobieraniu danych, zapisujemy buildera tworzącego dataTable
 * zawierający zapytanie z uwzględnieniem wybranych filtrów
 */
class PostBuildEvent extends Event
{
    /**
     * @var \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    protected $dataTableBuilder;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param DataTableBuilder $dataTableBuilder
     * @param $parameters
     */
    public function __construct(DataTableBuilder $dataTableBuilder, $parameters)
    {
        $this->dataTableBuilder = $dataTableBuilder;
        $this->parameters = $parameters;
    }

    /**
     * @return DataTableBuilder
     */
    public function getDataTableBuilder()
    {
        return $this->dataTableBuilder;
    }

    /**
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

}
