<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

use NetTeam\Bundle\DataTableBundle\Column\ValueGetter\AsIsValueGetter;

/**
 * Kolumna z tekstem wpisanym na sztywno
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class PlainTextColumn extends Column
{
    protected $template = 'plain_text_column';

    public function addGetter($getterKey)
    {
        $this->getters[] = new AsIsValueGetter($getterKey);

        return $this;
    }
}
