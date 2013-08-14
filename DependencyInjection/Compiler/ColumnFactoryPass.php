<?php

namespace NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler;

class ColumnFactoryPass extends AbstractColumnFactoryPass
{
    public function getColumns()
    {
        $columns = array(
            'array' => 'NetTeam\Bundle\DataTableBundle\Column\ArrayColumn',
            'bool' => 'NetTeam\Bundle\DataTableBundle\Column\BoolColumn',
            'date' => 'NetTeam\Bundle\DataTableBundle\Column\DateColumn',
            'date_time' => 'NetTeam\Bundle\DataTableBundle\Column\DateTimeColumn',
            'href' => 'NetTeam\Bundle\DataTableBundle\Column\HrefColumn',
            'href_plain_text' => 'NetTeam\Bundle\DataTableBundle\Column\HrefPlainTextColumn',
            'href_text' => 'NetTeam\Bundle\DataTableBundle\Column\HrefTextColumn',
            'interval' => 'NetTeam\Bundle\DataTableBundle\Column\IntervalColumn',
            'money' => 'NetTeam\Bundle\DataTableBundle\Column\MoneyColumn',
            'money_currency' => 'NetTeam\Bundle\DataTableBundle\Column\MoneyCurrencyColumn',
            'plain_text' => 'NetTeam\Bundle\DataTableBundle\Column\PlainTextColumn',
            'text' => 'NetTeam\Bundle\DataTableBundle\Column\TextColumn',
            'edit_in_place' => 'NetTeam\Bundle\DataTableBundle\Column\EditInPlaceColumn',
            'collection' => 'NetTeam\Bundle\DataTableBundle\Column\Collection\CollectionColumn',
            'custom' => 'NetTeam\Bundle\DataTableBundle\Column\CustomColumn',
        );

        return $columns;
    }
}
