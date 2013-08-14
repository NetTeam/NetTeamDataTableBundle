<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Column;

use NetTeam\Bundle\DataTableBundle\Column\CustomColumn;

/**
 * @author Paweł A. Wacławczyk <p.a.waclawczyk@gmail.com>
 */
class CustomColumnTest extends \PHPUnit_Framework_TestCase
{
    private $column;

    protected function setUp()
    {
        $this->column = new CustomColumn('caption', 'getter');
    }

    protected function tearDown()
    {
        $this->column = null;
    }

    public function testOverridenTemplateSetterAndGetter()
    {
        $this->column->setTemplate('PathToTwig');

        $this->assertEquals('PathToTwig', $this->column->getTemplate());
    }

    public function testIfNotOverridenTemplateThenReturnDefaultForStandardColumn()
    {
        $this->assertEquals('NetTeamDataTableBundle:Column:column.html.twig', $this->column->getTemplate());
    }
}
