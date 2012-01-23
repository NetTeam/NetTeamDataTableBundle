<?php

namespace NetTeam\System\DataTableBundle\Column;

/**
 * Description of ColumnFactory
 *
 */
class ColumnFactory
{
    private $columns = array(
        'text' => 'NetTeam\System\DataTableBundle\Column\TextColumn',
        'array' => 'NetTeam\System\DataTableBundle\Column\ArrayColumn',
        'date' => 'NetTeam\System\DataTableBundle\Column\DateColumn',
        'bool' => 'NetTeam\System\DataTableBundle\Column\BoolColumn',
        'money' => 'NetTeam\System\DataTableBundle\Column\MoneyColumn',
        'money_currency' => 'NetTeam\System\DataTableBundle\Column\MoneyCurrencyColumn',
        'plain_text' => 'NetTeam\System\DataTableBundle\Column\PlainTextColumn',
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
