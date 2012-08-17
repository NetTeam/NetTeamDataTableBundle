<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

class HrefPlainTextColumn extends HrefColumn
{

    public static function create($name, $getters, array $parameters)
    {
        $column = parent::create($name, $getters, $parameters);
        $column->addClass('to-right');

        return $column;
    }

    public function getTemplate()
    {
        return 'NetTeamLayoutBundle:Column:href_plaintext.html.twig';
    }

    protected function getColumn()
    {
        return 'NetTeam\Bundle\DataTableBundle\Column\PlainTextColumn';
    }

}
