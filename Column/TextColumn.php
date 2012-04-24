<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

use NetTeam\Bundle\DataTableBundle\Column\Value\ColumnValue;

/**
 * TextColumn
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@carryater.pl>
 */
class TextColumn extends Column
{
    protected $template = 'text_column';

    public function getValue($objectOrArray)
    {
        $columnValue = parent::getValue($objectOrArray);

        $value = $columnValue->getValue();
        if (is_array($value)) {
            $value = implode(' ', $value);
        }

        return new ColumnValue($value, $columnValue->getRoute(), $columnValue->getRouteParams(), $columnValue->getRouteClasses(), $columnValue->all());
    }
}
