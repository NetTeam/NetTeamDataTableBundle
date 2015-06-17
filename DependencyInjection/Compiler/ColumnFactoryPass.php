<?php

namespace NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler;

class ColumnFactoryPass extends AbstractColumnFactoryPass
{
    public function getColumns()
    {
        $columns = array(
            'array' => 'NetTeam\Bundle\DataTableBundle\Column\ArrayColumn',
            'bool' => 'NetTeam\Bundle\DataTableBundle\Column\BoolColumn',
            'checkbox' => 'NetTeam\Bundle\DataTableBundle\Column\CheckboxColumn',
            'date' => 'NetTeam\Bundle\DataTableBundle\Column\DateColumn',
            'date_time' => 'NetTeam\Bundle\DataTableBundle\Column\DateTimeColumn',
            'money' => 'NetTeam\Bundle\DataTableBundle\Column\MoneyColumn',
            'money_currency' => 'NetTeam\Bundle\DataTableBundle\Column\MoneyCurrencyColumn',
            'plain_text' => 'NetTeam\Bundle\DataTableBundle\Column\PlainTextColumn',
            'text' => 'NetTeam\Bundle\DataTableBundle\Column\TextColumn',
            'collection' => 'NetTeam\Bundle\DataTableBundle\Column\Collection\CollectionColumn',
            'custom' => 'NetTeam\Bundle\DataTableBundle\Column\CustomColumn',
        );

        return $columns;
    }
}
