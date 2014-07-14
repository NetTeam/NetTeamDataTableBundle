<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Source;

use NetTeam\Bundle\DataTableBundle\Source\DoctrineSQLSource;

/**
 * Class DoctrineSQLSourceTest
 * @group Unit
 */
class DoctrineSQLSourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\NativeQuery
     */
    private $nativeQuery;

    /**
     * setUp
     */
    public function setUp()
    {
        $entityManager = \Mockery::mock('Doctrine\ORM\EntityManager');
        // Not mock because class \Doctrine\ORM\NativeQuery is marked final
        $configurationORM = \Mockery::mock('\Doctrine\ORM\Configuration');
        $configurationORM->shouldReceive('isSecondLevelCacheEnabled')->andReturn(false);
        $configurationORM->shouldReceive('getDefaultQueryHints')->andReturn(array());

        $entityManager->shouldReceive('getConfiguration')->andReturn($configurationORM);

        $this->nativeQuery = new \Doctrine\ORM\NativeQuery($entityManager);

    }

    /**
     * Test adding sorting
     */
    public function testAddSorting()
    {
        $this->nativeQuery->setSql('SELECT * FROM users');

        $doctrineSQLSource = new DoctrineSQLSource($this->nativeQuery);
        $doctrineSQLSource->addSorting('name', 'ASC');

        $this->assertEquals("SELECT * FROM users ORDER BY name ASC", $this->nativeQuery->getSql());
    }

    /**
     * Test adding a double sorting
     */
    public function testAddSortingTwice()
    {
        $this->nativeQuery->setSql('SELECT * FROM users');

        $doctrineSQLSource = new DoctrineSQLSource($this->nativeQuery);
        $doctrineSQLSource->addSorting('name', 'ASC');
        $doctrineSQLSource->addSorting('surname', 'DESC');

        $this->assertEquals("SELECT * FROM users ORDER BY name ASC, surname DESC", $this->nativeQuery->getSql());
    }

    /**
     * tearDown
     */
    public function tearDown()
    {
        unset($this->nativeQuery);
    }
}
