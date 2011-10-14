<?php

namespace NetTeam\System\DataTableBundle\DataTable;

interface DataTableInterface
{
    function build(DataTableBuilder $builder);
    function getSource();
}