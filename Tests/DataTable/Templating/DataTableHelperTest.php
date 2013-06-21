<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\DataTable\Column;

use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;
use NetTeam\Bundle\DataTableBundle\Templating\Helper\DataTableHelper;
use Mockery as M;

/**
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 * @group Unit
 */
class DataTableHelperTest extends \PHPUnit_Framework_TestCase
{

    private $templating;
    private $dataTableFactory;
    private $dataTableBuilder;

    public function setUp()
    {
        $this->dataTableBuilder = M::mock('NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder');
        $this->dataTableBuilder->shouldReceive('getBulkActionsTemplate')->andReturn('bulkActionsTemplate');
        $this->dataTableBuilder->shouldReceive('getActionsTemplate')->andReturn('actionsTemplate');
        $this->dataTableFactory = M::mock('NetTeam\Bundle\DataTableBundle\DataTable\DataTableFactory');
        $this->templating = M::mock('Symfony\Component\Templating\EngineInterface');

    }

    public function testRender()
    {
        $this->templating->shouldReceive('render')->andReturn('rendered!');

        $this->dataTableFactory->shouldReceive('create')->andReturn($this->dataTableBuilder);

        $dataTableHelper =  new DataTableHelper($this->templating, $this->dataTableFactory);
        $this->assertSame('rendered!', $dataTableHelper->render('test_name'));
        $this->assertSame(null, $dataTableHelper->renderJavascripts());

    }

    public function testRenderWithJs()
    {
        $this->templating->shouldReceive('render')->andReturn('rendered!');

        $this->dataTableFactory->shouldReceive('create')->andReturn($this->dataTableBuilder);

        $dataTableHelper =  new DataTableHelper($this->templating, $this->dataTableFactory);
        $this->assertSame('rendered!', $dataTableHelper->render('test_name', array(), array('with_js' => false)));
        $this->assertSame('rendered!', $dataTableHelper->renderJavascripts());

    }

}
