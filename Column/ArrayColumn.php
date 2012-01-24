<?php

namespace NetTeam\System\DataTableBundle\Column;

/**
 * ArrayColumn
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@carryater.pl>
 */
class ArrayColumn extends Column
{
    protected $template = 'array_column';

    protected $separator = ', ';

    public function getValue($objectOrArray)
    {
        $value = parent::getValue($objectOrArray);
        $value->add('separator', $this->separator);

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
}
