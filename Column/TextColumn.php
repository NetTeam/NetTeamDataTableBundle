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
    protected $separator = ' ';
    protected $raw = false;

    public function getValue($objectOrArray)
    {
        $columnValue = parent::getValue($objectOrArray);

        $value = $columnValue->getValue();
        if (is_array($value)) {
            $value = implode($this->separator, $value);
        }

        $value = new ColumnValue($value, $columnValue->getRoute(), $columnValue->getRouteParams(), $columnValue->getRouteClasses(), $columnValue->all());
        $value->add('raw', $this->raw);

        return $value;
    }

    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    public function separator($separator)
    {
        return $this->setSeparator($separator);
    }

    public function separatorNewLine()
    {
        return $this->setSeparator('<br />');
    }

    public function raw()
    {
        $this->raw = true;

        return $this;
    }

}
