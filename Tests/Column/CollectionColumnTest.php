<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Column;

use NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory;
use NetTeam\Bundle\DataTableBundle\Column\Column;
use NetTeam\Bundle\DataTableBundle\Column\Collection\CollectionColumn;

/**
 * Testy jednostkowe dla CollectionColumn
 *
 * @author Piotr Walków <piotr.walkow@netteam.pl>
 * @group Unit
 */
class CollectionColumnTest extends \PHPUnit_Framework_TestCase
{
    private $columnFactory;

    private $collectionColumn;

    private $column;

    public function setUp()
    {
        $this->column = \Mockery::mock('NetTeam\Bundle\DataTableBundle\Column\Column');

        $this->columnFactory = \Mockery::mock('NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory');

        $this->collectionColumn = new CollectionColumn('caption', 'getter', array());
        $this->collectionColumn->setColumnFactory($this->columnFactory);
    }

    public function testAddColumnToCollection()
    {
        $this->columnFactory
                ->shouldReceive('create')
                ->twice()
                ->with(\Mockery::anyOf('mockColumn1', 'mockColumn2'), \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn($this->column);

        $this->collectionColumn
                ->add('mockColumn1', '', '')
                ->next()
                ->add('mockColumn2', '', '');

        $this->assertEquals(2, count($this->collectionColumn->getColumnCollection()));

    }

    public function testGetValueOnCollection()
    {
        $mockColumn1 = \Mockery::mock('NetTeam\Bundle\DataTableBundle\Column\Column', array(
            'getTemplate' => '123',
        ));

        $mockColumn2 = \Mockery::mock('NetTeam\Bundle\DataTableBundle\Column\Column', array(
            'getTemplate' => '321',
        ));

        $this->columnFactory
                ->shouldReceive('create')
                ->twice()
                ->andReturn($mockColumn1, $mockColumn2);

        $mockColumn1->shouldReceive('getValue')->once()->andReturn('123');
        $mockColumn2->shouldReceive('getValue')->andReturn('321');

        $this->collectionColumn
                ->add($mockColumn1)
                ->next()
                ->add($mockColumn2);

        $this->collectionColumn->getValue(array());

    }

}
