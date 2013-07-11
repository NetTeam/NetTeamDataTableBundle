<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Form\Extension;

use NetTeam\Bundle\DataTableBundle\Form\Extension\DateTypeExtension;
use Mockery as M;

/**
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 * @group Unit
 */
class DateTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $formBuilder;
    protected $formatter;
    public function setUp()
    {
        $this->formBuilder = M::mock('Symfony\Component\Form\FormBuilder');
        $this->formBuilder->shouldReceive('hasAttribute')->once()->with('attr')->andReturn(true);
        $this->formBuilder->shouldReceive('hasAttribute')->once()->with('formatter')->andReturn(true);

        $this->formatter = new \IntlDateFormatter(
            \Locale::getDefault(),
            \IntlDateFormatter::MEDIUM,
            \IntlDateFormatter::NONE
        );

        $this->formBuilder->shouldReceive('getAttribute')->once()->with('formatter')->andReturn($this->formatter);
        $this->formBuilder->shouldReceive('getAttribute')->once()->with('attr')->andReturn(array('data-filter-default' => new \DateTime('2013-07-09')));

    }

    public function testBuildForm()
    {
        $this->formBuilder->shouldReceive('getAttribute')->once()->with('attr')->andReturn(array('data-filter-default' => new \DateTime('2013-07-09')));
        $this->formBuilder->shouldReceive('setAttribute')->once()->with('attr', array('data-filter-default' => $this->formatter->format(new \DateTime('2013-07-09'))));
        $dateTypeExtension = new DateTypeExtension();
        $dateTypeExtension->buildForm($this->formBuilder, array());
    }

    public function testBuildFormNoFilterDefaultOption()
    {
        $this->formBuilder->shouldReceive('getAttribute')->once()->with('attr')->andReturn(array());
        $this->formBuilder->shouldReceive('setAttribute')->never();

        $dateTypeExtension = new DateTypeExtension();

        $dateTypeExtension->buildForm($this->formBuilder, array());
    }
    public function tearDown()
    {
        unset($this->formBuilder);
    }

}
