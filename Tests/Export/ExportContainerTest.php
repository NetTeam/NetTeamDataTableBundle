<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Export;

use NetTeam\Bundle\DataTableBundle\Export\ExportContainer;

class ExportContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExportContainer
     */
    protected $exportContainer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->export = \Mockery::mock('NetTeam\Bundle\DataTableBundle\Export\ExportInterface');
        $this->exportContainer = new ExportContainer();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->export = null;
        $this->exportContainer = null;
    }

    public function testAdd()
    {
        $export1 = $this->getMockExport()
            ->shouldReceive('getName')->andReturn('extension_type1')->once()
            ->getMock();

        $export2 = $this->getMockExport()
            ->shouldReceive('getName')->andReturn('extension_type2')->once()
            ->getMock();

        $this->exportContainer->add($export1);
        $this->exportContainer->add($export2);
        $this->assertSame($export1, $this->exportContainer->get('extension_type1'));
        $this->assertSame($export2, $this->exportContainer->get('extension_type2'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddTwice()
    {
        $export = $this->getMockExport()
            ->shouldReceive('getName')->andReturn('extension_type')->twice()
            ->getMock();

        $this->exportContainer->add($export);
        $this->exportContainer->add($export);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidNameProvider
     */
    public function testInvalidName($name)
    {
        $export = $this->getMockExport()
            ->shouldReceive('getName')->andReturn($name)
            ->getMock();

        $this->exportContainer->add($export);
    }

    public function invalidNameProvider()
    {
        return array(
            array(null),
            array(''),
            array(new \DateTime())
        );
    }

    protected function getMockExport()
    {
        return \Mockery::mock('NetTeam\Bundle\DataTableBundle\Export\ExportInterface');
    }
}
