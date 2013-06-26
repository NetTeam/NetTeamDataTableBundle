<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Service;

use NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory;
use NetTeam\Bundle\DataTableBundle\Column\Column;

/**
 * Testy jednostkowe dla ColumnFactory
 *
 * @author Piotr Walków <piotr.walkow@netteam.pl>
 * @group Unit
 */
class ColumnFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $columnFactory;

    protected function setUp()
    {
        $this->columnFactory = new ColumnFactory();
    }

    protected function tearDown()
    {
        $this->columnFactory = null;
    }

    public function testAddColumnType()
    {
        $name = 'mock';
        $class = 'NetTeam\Bundle\DataTableBundle\Tests\Service\MockColumn';

        $this->columnFactory->addColumnType($name, $class);
    }

    /**
     * @expectedException \NetTeam\Bundle\DataTableBundle\Factory\Exception\WrongColumnTypeException
     */
    public function testInvalidNameAddColumnType()
    {
        $name = array();
        $class = 'NetTeam\Bundle\DataTableBundle\Tests\Service\MockColumn';

        $this->columnFactory->addColumnType($name, $class);
    }

    /**
     * @expectedException \NetTeam\Bundle\DataTableBundle\Factory\Exception\InvalidColumnClassException
     */
    public function testInvalidClassNameAddColumnType()
    {
        $name = 'mock';
        $class = 'NetTeam\Bundle\DataTableBundle\Tests\Service\NonExistingMockColumn';

        $this->columnFactory->addColumnType($name, $class);
    }

    /**
     * @expectedException \NetTeam\Bundle\DataTableBundle\Factory\Exception\ColumnExistException
     */
    public function testColumnAlreadyExistAddColumnType()
    {
        $name = 'mock';
        $class = 'NetTeam\Bundle\DataTableBundle\Tests\Service\MockColumn';

        $this->columnFactory->addColumnType($name, $class);

        $this->columnFactory->addColumnType($name, $class);
    }

    public function testCreateFromColumnObject()
    {
        $column = \Mockery::mock('NetTeam\Bundle\DataTableBundle\Column\Column');

        $columnCreated = $this->columnFactory->create($column, '', '');

        $this->assertSame($column, $columnCreated);

    }

    /**
     * @expectedException \NetTeam\Bundle\DataTableBundle\Factory\Exception\WrongColumnTypeException
     */
    public function testCreateInvalidType()
    {
        $column = \Mockery::mock('NetTeam\Bundle\DataTableBundle\Column\Column');

        $columnCreated = $this->columnFactory->create(11, '', '');
    }

    /**
     * @expectedException \NetTeam\Bundle\DataTableBundle\Factory\Exception\WrongColumnTypeParameterException
     */
    public function testCreateInvalidName()
    {
        $name = 'mock';
        $class = 'NetTeam\Bundle\DataTableBundle\Tests\Service\MockColumn';

        $this->columnFactory->addColumnType($name, $class);

        $columnCreated = $this->columnFactory->create('non_mock', '', '');
    }

}

class MockColumn extends Column
{
}
