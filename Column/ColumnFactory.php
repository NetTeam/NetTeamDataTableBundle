<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

/**
 * Description of ColumnFactory
 *
 */
class ColumnFactory
{
    /**
     * @todo Wydzielic to scierwo
     *
     */
    private $columns = array(
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

    public function create($type, $name, $getter, array $parameters = array())
    {
        if (!is_string($type)) {
            throw new \InvalidArgumentException('Wrong column type parameter, string expected');
        }
        if (!array_key_exists($type, $this->columns)) {
            throw new \InvalidArgumentException('Wrong column type, "'.$type.'" given');
        }
        $columnClass = $this->columns[$type];

        return $columnClass::create($name, $getter, $parameters);
    }

}
