<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\StateStorage;

use NetTeam\Bundle\DataTableBundle\StateStorage\DataTableSessionStateStorage;
use Mockery as M;

/**
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 */
class DataTableSessionStateStorageTest extends \PHPUnit_Framework_TestCase
{

    private $session;
    private $dataTableBuilder;

    protected function setUp()
    {
        $this->session = M::mock('Symfony\Component\HttpFoundation\Session');

        $this->dataTableBuilder = M::mock('NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder');
        $this->dataTableBuilder->shouldReceive('getContext')->andReturn(array('context'));
        $this->dataTableBuilder->shouldReceive('getName')->andReturn('test');

    }

    public function testGet()
    {
        $key = 'test'.md5(serialize($this->dataTableBuilder->getContext()));

        $this->session->shouldReceive('get')->andReturn(array($key => 'test'));
        $this->session->shouldReceive('set');

        $filterStorage = new DataTableSessionStateStorage($this->session);
        $filterStorage->set($this->dataTableBuilder, array('test'));
        $this->assertEquals('test', $filterStorage->get($this->dataTableBuilder)) ;
    }

    public function testGetNull()
    {
        $this->session->shouldReceive('get')->andReturn(null);
        $this->session->shouldReceive('set')->never();

        $filterStorage = new DataTableSessionStateStorage($this->session);
        $this->assertNull($filterStorage->get($this->dataTableBuilder)) ;
    }

    public function testHasTrue()
    {
        $key = 'test'.md5(serialize($this->dataTableBuilder->getContext()));

        $this->session->shouldReceive('get')->andReturn(array($key => 'test'));
        $this->session->shouldReceive('set');

        $filterStorage = new DataTableSessionStateStorage($this->session);
        $filterStorage->set($this->dataTableBuilder, array('test'));
        $this->assertTrue($filterStorage->has($this->dataTableBuilder)) ;
    }

    public function testHasFalse()
    {
        $this->session->shouldReceive('get')->andReturn(null);
        $this->session->shouldReceive('set')->never();

        $filterStorage = new DataTableSessionStateStorage($this->session);
        $this->assertFalse($filterStorage->has($this->dataTableBuilder)) ;
    }

    protected function tearDown()
    {
        $this->dataTableBuilder = null;
        $this->session = null;
    }

}
