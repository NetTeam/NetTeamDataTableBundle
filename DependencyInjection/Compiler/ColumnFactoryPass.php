<?php

namespace NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ColumnFactoryPass implements ColumnFactoryPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('nt_datatable.factory.column')) {
            return;
        }

        $columns = $this->getColumns();
        
        foreach ($columns as $name => $class) {
            $container->getDefinition('nt_datatable.factory.column')->addMethodCall('addColumnType', array($name, $class));
        }
    }
    
    public function getColumns() {
        $columns = array(
            'array' => 'NetTeam\Bundle\DataTableBundle\Column\ArrayColumn',
            'bool' => 'NetTeam\Bundle\DataTableBundle\Column\BoolColumn',
            'date' => 'NetTeam\Bundle\DataTableBundle\Column\DateColumn',
            'date_time' => 'NetTeam\Bundle\DataTableBundle\Column\DateTimeColumn',
            'money' => 'NetTeam\Bundle\DataTableBundle\Column\MoneyColumn',
            'money_currency' => 'NetTeam\Bundle\DataTableBundle\Column\MoneyCurrencyColumn',
            'plain_text' => 'NetTeam\Bundle\DataTableBundle\Column\PlainTextColumn',
            'text' => 'NetTeam\Bundle\DataTableBundle\Column\TextColumn',
            'collection' => 'NetTeam\Bundle\DataTableBundle\Column\Collection\CollectionColumn',
        );
        
        return $columns;
    }
}
