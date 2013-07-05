<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Tests\Functional;

use NetTeam\Bundle\DataTableBundle\Tests\Functional\DatatableFilter;

/**
 * @group Unit
 */
class DatatableFilterTest extends \PHPUnit_Framework_TestCase
{
    private $filter;

    protected function setUp()
    {
        $this->filter = new DatatableFilter();
    }

    protected function tearDown()
    {
        $this->filter = null;
    }

    public function testEmpty()
    {
        $this->assertEmpty($this->filter->getQueryString('test'));
    }

    public function testSetValue()
    {
        $this->filter->setValue('123', 'xxx');
        $this->filter->setValue('999', 'yyy');

        $query = $this->filter->getQueryString('test');

        $this->assertContains('123', $query);
        $this->assertContains('xxx', $query);
        $this->assertContains('999', $query);
        $this->assertContains('yyy', $query);

        $this->assertContains('[value]', $query);
        $this->assertContains('[value]', $query);
    }

    public function testSetChoice()
    {
        $this->filter->setChoice('123', 'xxx');
        $this->filter->setChoice('999', 'yyy');

        $query = $this->filter->getQueryString('test');

        $this->assertContains('123', $query);
        $this->assertContains('xxx', $query);
        $this->assertContains('999', $query);
        $this->assertContains('yyy', $query);

        $this->assertContains('[choice]', $query);
        $this->assertContains('[choice]', $query);
    }

}
