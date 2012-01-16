<?php

namespace NetTeam\System\DataTableBundle\Extras;

class TableTools
{


    private $csv;
    private $enabled;

    public function __construct()
    {
        $this->csv = false;
        $this->enabled = false;
    }

    public function hasCsv()
    {
        return $this->csv;
    }

    public function setCsv($csv)
    {
        $this->csv = $csv;
        $this->enable();
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function enable()
    {
        $this->enabled = true;
    }

    public function disable()
    {
        $this->enabled = false;
    }

}
