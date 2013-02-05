<?php

use NetTeam\Bundle\DataTableBundle\Column\ValueGetter\PropertyValueGetter;

/**
 * Description of PropertyValueGetterTest
 *
 * @author Paweł A. Wacławczyk <pawel.waclawczyk@netteam.pl>
 */
class PropertyValueGetterTest extends \PHPUnit_Framework_TestCase
{

    private $testObject;

    public function setUp()
    {
        parent::setUp();
        $this->testObject = new TestClass();
        $this->testObject->nestedObject = new TestNestedClass();
    }

    public function testGettingValueFromPublicProperty()
    {
        $this->testObject->publicProperty = 'publicPropertyValue';

        $propertyValueGetter = new PropertyValueGetter('publicProperty');
        $this->assertEquals('publicPropertyValue', $propertyValueGetter->get($this->testObject));
    }

    public function testGettingValueFromPrivateProperty()
    {
        $this->testObject->setPrivateProperty('privatePropertyValue');

        $propertyValueGetter = new PropertyValueGetter('privateProperty');
        $this->assertEquals('privatePropertyValue', $propertyValueGetter->get($this->testObject));
    }

    public function testGettingValueFromNestedObject()
    {
        $this->testObject->nestedObject->nestedProperty = 'nestedValue';

        $propertyValueGetter = new PropertyValueGetter('nestedObject.nestedProperty');
        $this->assertEquals('nestedValue', $propertyValueGetter->get($this->testObject));
    }

    public function testGettingValueFromArray()
    {
        $testArray = array(
            'arrayKey' => 'arrayValue',
        );

        $propertyValueGetter = new PropertyValueGetter('[arrayKey]');
        $this->assertEquals('arrayValue', $propertyValueGetter->get($testArray));
    }

}

class TestClass
{

    public $publicProperty;
    private $privateProperty;
    public $nestedObject;

    public function setPrivateProperty($value)
    {
        $this->privateProperty = $value;
    }

    public function getPrivateProperty()
    {
        return $this->privateProperty;
    }

}

class TestNestedClass
{

    public $nestedProperty;

}
