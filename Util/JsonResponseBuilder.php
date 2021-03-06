<?php

namespace NetTeam\Bundle\DataTableBundle\Util;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;

/**
 * Description of JsonResponseBuilder
 *
 * @author zuo
 */
final class JsonResponseBuilder
{
    protected $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function build(array $data, DataTableBuilder $builder, $alias, $count, $echo, $filterSessionHash = null)
    {
        return new Response(json_encode(array(
            'sEcho' => $echo,
            'iTotalRecords' => $count,
            'iTotalDisplayRecords' => $count,
            'aaData'=> $this->buildData($data, $builder, $alias),
            'filterSessionHash' => $filterSessionHash
        )));
    }

    private function buildData(array $data, DataTableBuilder $builder, $alias)
    {
        $built = array();

        $columns = $builder->getColumns();
        $bulkTemplate = $builder->getBulkActionsColumn()->getTemplate();
        $bulkColumn = $builder->getBulkActionsColumn();

        foreach ($data as $rowKey => $row) {
            if ($builder->getBulkActions()) {
                $built[$rowKey][] = $this->templating->render('NetTeamDataTableBundle:BulkAction:' . $bulkTemplate . '.html.twig', array(
                    'key'    => $rowKey,
                    'value'  => $row['bulk'],
                    'column' => $bulkColumn,
                    'alias'  => $alias
                ));
            }

            foreach ($row['columns'] as $key => $value) {
                $column = $columns[$key];
                $parameters = array_merge(
                    $column->getParameters(),
                    array('record' => $value)
                );
                $built[$rowKey][] = $this->templating->render($column->getTemplate(), $parameters);
            }
        }

        return $built;
    }
}
