<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Export;

use NetTeam\Bundle\DataTableBundle\Export\ExportContainer;

/**
 * Testy dla ExportContainer
 *
 * @author Paweł Macyszyn <pawel.macyszyn@netteam.pl>
 */
class ExportContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonStringArgument()
    {
        $exportContainer = new ExportContainer($this->getMockContainer(), array());

        $exportContainer->get(new \DateTime());
    }

    /**
     * @expectedException NetTeam\Bundle\DataTableBundle\Export\Exception\ExportNotFoundException
     */
    public function testNonExistingAlias()
    {
        $exportContainer = new ExportContainer($this->getMockContainer(), array());

        $exportContainer->get('alias');
    }

    /**
     * @expectedException NetTeam\Bundle\DataTableBundle\Export\Exception\ExportContainerInvalidResultException
     */
    public function testInvalidReturn()
    {
        $container = $this->getMockContainer()
            ->shouldReceive('get')->andReturn('im not implementing ExportInterface')->once()
            ->getMock();

        $exportContainer = new ExportContainer($container, array('alias' => 'name'));

        $exportContainer->get('alias');
    }

    public function testValidGet()
    {
        $container = $this->getMockContainer()
            ->shouldReceive('get')->andReturn($this->getMockExport())->once()
            ->getMock();

        $exportContainer = new ExportContainer($container, array('alias' => 'name'));

        $export = $exportContainer->get('alias');
        $this->assertInstanceOf('NetTeam\Bundle\DataTableBundle\Export\ExportInterface', $export);
    }

    protected function getMockContainer()
    {
        return \Mockery::mock('Symfony\Component\DependencyInjection\ContainerInterface');
    }

    protected function getMockExport()
    {
        return \Mockery::mock('NetTeam\Bundle\DataTableBundle\Export\ExportInterface');
    }
}
