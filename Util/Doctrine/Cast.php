<?php

namespace NetTeam\Bundle\DataTableBundle\Util\Doctrine;

/**
 * CAST DQL function
 */
class Cast
{
    private $_name;
    private $value;
    private $type;

    public function __construct($value, $type)
    {
        $this->_name = 'CAST';
        $this->value = $value;
        $this->type = $type;
    }

    public function __toString()
    {
        return $this->_name . '( ' . $this->value . ',' . $this->type . ')';
    }

}