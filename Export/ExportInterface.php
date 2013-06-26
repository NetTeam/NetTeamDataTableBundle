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
     * @param  string   $title
     * @param  array    $columns
     * @param  array    $data
     * @return Response
     */
    public function export($title, array $columns, array $data);

    /**
     * Nazwa identyfikująca serwis eksportujący
     *
     * @return string
     */
    public function getName();
}
