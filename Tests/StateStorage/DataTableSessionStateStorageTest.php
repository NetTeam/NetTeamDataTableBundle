<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\StateStorage;

use NetTeam\Bundle\DataTableBundle\StateStorage\DataTableSessionStateStorage;
use Mockery as M;
use Symfony\Component\HttpFoundation\Session;

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

        $stateStorage = new DataTableSessionStateStorage($this->session);
        $stateStorage->set($this->dataTableBuilder, array('test'));
        $this->assertEquals('test', $stateStorage->get($this->dataTableBuilder)) ;
    }

    public function testGetNull()
    {
        $this->session->shouldReceive('get')->andReturn(null);
        $this->session->shouldReceive('set')->never();

        $stateStorage = new DataTableSessionStateStorage($this->session);
        $this->assertNull($stateStorage->get($this->dataTableBuilder)) ;
    }

    public function testHasTrue()
    {
        $key = 'test'.md5(serialize($this->dataTableBuilder->getContext()));

        $this->session->shouldReceive('get')->andReturn(array($key => 'test'));
        $this->session->shouldReceive('set');

        $stateStorage = new DataTableSessionStateStorage($this->session);
        $stateStorage->set($this->dataTableBuilder, array('test'));
        $this->assertTrue($stateStorage->has($this->dataTableBuilder)) ;
    }

    public function testHasFalse()
    {
        $this->session->shouldReceive('get')->andReturn(null);
        $this->session->shouldReceive('set')->never();

        $stateStorage = new DataTableSessionStateStorage($this->session);
        $this->assertFalse($stateStorage->has($this->dataTableBuilder)) ;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetShouldThrowExceptionIfContextCannotBeConvertedToString()
    {
        $context = array(
            'not_string' => new \stdClass(),
        );

        $this->session->shouldIgnoreMissing();

        $dataTableBuilder = M::mock('NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder', array(
            'getName' => 'text',
            'getContext' => $context,
        ));

        $stateStorage = new DataTableSessionStateStorage($this->session);
        $stateStorage->set($dataTableBuilder, array('test'));
    }

    public function testSetShouldNormalizeContext()
    {
        $locale = setlocale(LC_NUMERIC, 0);
        setlocale(LC_ALL, 'en_US.UTF-8');

        $context = array(
            'integer' => 1,
            'float' => 1.567,
            'boolean' => true,
            'array' => array(
                'integer' => 25,
                'array' => array(
                    'float' => 12.889,
                ),
            ),
        );

        $normalizedContext = array(
            'integer' => '1',
            'float' => '1.567',
            'boolean' => '1',
            'array' => array(
                'integer' => '25',
                'array' => array(
                    'float' => '12.889',
                ),
            ),
        );

        $dataTableBuilder1 = M::mock('NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder', array(
            'getName' => 'text',
            'getContext' => $context,
        ));

        $dataTableBuilder2 = M::mock('NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder', array(
            'getName' => 'text',
            'getContext' => $normalizedContext,
        ));

        $sessionStorage = \Mockery::mock('Symfony\Component\HttpFoundation\SessionStorage\SessionStorageInterface')->shouldIgnoreMissing();
        $session = new Session($sessionStorage);

        $stateStorage = new DataTableSessionStateStorage($session);
        $stateStorage->set($dataTableBuilder1, array('test'));

        $this->assertTrue($stateStorage->has($dataTableBuilder2));
        setlocale(LC_ALL, $locale);
    }

    protected function tearDown()
    {
        $this->dataTableBuilder = null;
        $this->session = null;
    }

}
