<?php

namespace NetTeam\Bundle\DataTableBundle\Column\Value;

/**
 *
 * @author zuo
 */
interface ColumnValueInterface
{
    public function getValue();

    public function getRoute();
    public function getRouteParams();
    public function getRouteClasses();

    public function add($name, $value);
    public function get($name);
}
