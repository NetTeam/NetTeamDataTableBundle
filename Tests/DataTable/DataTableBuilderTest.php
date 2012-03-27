<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\DataTable;

use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;
use NetTeam\Bundle\DataTableBundle\Column\TextColumn;

/**
 * Description of CoreBundleTest
 *
 * @author wku
 */
class DataTableBuilderTest extends \PHPUnit_Framework_TestCase
{

    protected $data;

    public function setUp()
    {
        $this->data = array(
            array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09',),
            array('10', '11', '12', '13', '14', '15', '16', '17', '18', '19',),
            array('20', '21', '22', '23', '24', '25', '26', '27', '28', '29',),
            array('30', '31', '32', '33', '34', '35', '36', '37', '38', '39',),
            array('40', '41', '42', '43', '44', '45', '46', '47', '48', '49',),
            array('50', '51', '52', '53', '54', '55', '56', '57', '58', '59',),
            array('60', '61', '62', '63', '64', '65', '66', '67', '68', '69',),
            array('70', '77', '72', '73', '74', '75', '76', '77', '78', '79',),
            array('80', '81', '82', '83', '84', '85', '86', '87', '88', '89',),
            array('90', '91', '92', '93', '94', '95', '96', '97', '98', '99',)
        );
    }

    public function testBuild()
    {
        $source = $this->getMock('NetTeam\Bundle\DataTableBundle\Source\SourceInterface');
        $dtb = new DataTableBuilder('test_list', $source);

        $col = new TextColumn('Kol2', '1');
        $dtb->addColumn($col);

        $col = new TextColumn('Kol4', '3');
        $dtb->addColumn($col);

        $this->assertEquals(2, $dtb->countColumns());
    }
}
