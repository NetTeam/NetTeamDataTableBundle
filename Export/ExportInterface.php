<?php

namespace NetTeam\Bundle\DataTableBundle\Export;

/**
 * Interfejs dla serwisów służących do eksportowania danych z DataTable
 *
 * @author Paweł Macyszyn <pawel.macyszyn@netteam.pl>
 */
interface ExportInterface
{
    /**
     * Eksportuje dane wejściowe
     *
     * @param  string   $filename
     * @param  array    $columns
     * @param  array    $data
     * @param  array    $options
     * @return Response
     */
    public function export($filename, array $columns, array $data, array $options = array());

    /**
     * Nazwa identyfikująca serwis eksportujący
     *
     * @return string
     */
    public function getName();
}
