<?php

namespace NetTeam\System\DataTableBundle\Export;

/**
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 */
interface ExportInterface
{
    public function build();
    public function setOption($key, $value);
    public function setOptions($options);
    public function getOptions($value);
    public function getDefaultOptions();
    public function getHeaders();
    public function getLabel();
    public function getTemplate();
}
?>
