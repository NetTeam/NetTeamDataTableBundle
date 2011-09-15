<?php

namespace NetTeam\System\DataTableBundle;

interface DataTableInterface
{
    function build(DataTableBuilder $builder);
    function getSource();
}