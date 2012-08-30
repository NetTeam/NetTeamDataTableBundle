<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

class HrefTextColumn extends HrefColumn
{

    public function getTemplate()
    {
        return 'NetTeamLayoutBundle:Column:href_text.html.twig';
    }

    protected function getColumn()
    {
        return 'NetTeam\Bundle\DataTableBundle\Column\TextColumn';
    }

}
