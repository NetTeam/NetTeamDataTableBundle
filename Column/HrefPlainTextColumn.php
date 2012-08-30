<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

class HrefPlainTextColumn extends HrefColumn
{

    public function getTemplate()
    {
        return 'NetTeamLayoutBundle:Column:href_plaintext.html.twig';
    }

    protected function getColumn()
    {
        return 'NetTeam\Bundle\DataTableBundle\Column\PlainTextColumn';
    }

}
