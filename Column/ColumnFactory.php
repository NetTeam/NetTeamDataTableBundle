<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

/**
 * Description of ColumnFactory
 *
 */
class ColumnFactory
{
    private $columns = array(
        'text' => 'NetTeam\Bundle\DataTableBundle\Column\TextColumn',
        'array' => 'NetTeam\Bundle\DataTableBundle\Column\ArrayColumn',
        'date' => 'NetTeam\Bundle\DataTableBundle\Column\DateColumn',
        'bool' => 'NetTeam\Bundle\DataTableBundle\Column\BoolColumn',
        'money' => 'NetTeam\Bundle\DataTableBundle\Column\MoneyColumn',
        'money_currency' => 'NetTeam\Bundle\DataTableBundle\Column\MoneyCurrencyColumn',
        'plain_text' => 'NetTeam\Bundle\DataTableBundle\Column\PlainTextColumn',
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
