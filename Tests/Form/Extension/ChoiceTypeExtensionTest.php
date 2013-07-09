<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Form\Extension;

use NetTeam\Bundle\DataTableBundle\Form\Extension\ChoiceTypeExtension;
use Mockery as M;

/**
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 * @group Unit
 */
class ChoiceTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $formBuilder;

    public function setUp()
    {
        $this->formBuilder = M::mock('Symfony\Component\Form\FormBuilder');
        $this->formBuilder->shouldReceive('hasAttribute')->once()->with('attr')->andReturn(true);
        $this->formBuilder->shouldReceive('getAttribute')->once()->with('attr')->andReturn(array('data-filter-default' => json_encode(array('test1','test2'))));
        $this->formBuilder->shouldReceive('setAttribute')->once()->with('attr', array('data-filter-default' => json_encode(array('test1','test2'))));
    }

    public function testBuildForm()
    {
        $dateTypeExtension = new ChoiceTypeExtension();
        $dateTypeExtension->buildForm($this->formBuilder, array());
    }

    public function tearDown()
    {
        unset($this->formBuilder);
    }

}
