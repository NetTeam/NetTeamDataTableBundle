<?php

namespace NetTeam\Bundle\DataTableBundle\DataTable;

interface DataTableInterface
{

    public function build(DataTableBuilder $builder);

    public function getSource();

    public function getRequiredOptions();

    public function setOptions(array $options);

    public function getOption($name);

    public function getOptions();

}
