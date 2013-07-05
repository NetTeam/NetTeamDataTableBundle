<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use NetTeam\Bundle\DataTableBundle\Tests\Functional\DatatableTest;
use NetTeam\Bundle\DataTableBundle\Tests\Functional\DatatableFilter;

/**
 * @group Unit
 */
class DatatableTestTest extends \PHPUnit_Framework_TestCase
{
    const VALID_RESPONSE = '{}';
    const INVALID_RESPONSE = 'non-json-text';

    private $datatable;
    private $client;
    private $filters;

    protected function setUp()
    {
        $this->client    = \Mockery::mock('Symfony\Bundle\FrameworkBundle\Client');
        $this->filters   = \Mockery::mock('NetTeam\Bundle\DataTableBundle\Tests\Functional\DatatableFilter');
        $this->datatable = new DatatableTest($this->client, 'test');
    }

    protected function tearDown()
    {
        $this->datatable = null;
        $this->filters   = null;
        $this->client    = null;
    }

    public function testAssertResponseWithoutFiltersValid()
    {
        $this->client
            ->shouldReceive('request')
            ->once()
            ->with('GET', DatatableTest::URL . '/test');

        $response = \Mockery::mock('Symfony\Component\HttpFoundation\Response', array(
            'getContent' => self::VALID_RESPONSE,
            'isOk' => true,
        ));

        $this->client
            ->shouldReceive('getResponse')
            ->andReturn($response)
        ;

        $this->datatable->assertResponse();
    }

    public function testAssertResponseWithoutFiltersAndWithQueryParamsValid()
    {
        $this->client
            ->shouldReceive('request')
            ->once()
            ->with('GET', DatatableTest::URL . '/test?aaa=AAA&bbb=BBB');

        $response = \Mockery::mock('Symfony\Component\HttpFoundation\Response', array(
            'getContent' => self::VALID_RESPONSE,
            'isOk' => true,
        ));

        $this->client
            ->shouldReceive('getResponse')
            ->andReturn($response)
        ;

        $this->datatable->setQueryParams(array(
            'aaa' => 'AAA',
            'bbb' => 'BBB',
        ));
        $this->datatable->assertResponse();
    }

    /**
     * @expectedException PHPUnit_Framework_ExpectationFailedException
     */
    public function testAssertResponseWithoutFiltersIsNotOk()
    {
        $this->client
            ->shouldReceive('request')
            ->once()
            ->with('GET', DatatableTest::URL . '/test');

        $response = \Mockery::mock('Symfony\Component\HttpFoundation\Response', array(
            'getContent' => self::VALID_RESPONSE,
            'isOk' => false,
        ));

        $this->client
            ->shouldReceive('getResponse')
            ->andReturn($response)
        ;

        $this->datatable->assertResponse();
    }

    /**
     * @expectedException PHPUnit_Framework_ExpectationFailedException
     */
    public function testAssertResponseWithoutFiltersInvalidContent()
    {
        $this->client
            ->shouldReceive('request')
            ->once()
            ->with('GET', DatatableTest::URL . '/test');

        $response = \Mockery::mock('Symfony\Component\HttpFoundation\Response', array(
            'getContent' => self::INVALID_RESPONSE,
            'isOk' => true,
        ));

        $this->client
            ->shouldReceive('getResponse')
            ->andReturn($response)
        ;

        $this->datatable->assertResponse();
    }

    public function testAssertResponseWithFiltersValid()
    {
        $this->filters
            ->shouldReceive('getQueryString')
            ->once()
            ->andReturn(implode('&', array('aaa', 'bbb')));

        $this->client
            ->shouldReceive('request')
            ->once()
            ->with('GET', DatatableTest::URL . '/test?aaa&bbb');

        $response = \Mockery::mock('Symfony\Component\HttpFoundation\Response', array(
            'getContent' => self::VALID_RESPONSE,
            'isOk' => true,
        ));

        $this->client
            ->shouldReceive('getResponse')
            ->andReturn($response)
        ;

        $this->datatable->assertResponse($this->filters);
    }

    public function testAssertResponseWithFiltersAndQueryParamsValid()
    {
        $this->filters
            ->shouldReceive('getQueryString')
            ->once()
            ->andReturn(implode('&', array('aaa', 'bbb')));

        $this->client
            ->shouldReceive('request')
            ->once()
            ->with('GET', DatatableTest::URL . '/test?ccc=CCC&ddd=DDD&aaa&bbb');

        $response = \Mockery::mock('Symfony\Component\HttpFoundation\Response', array(
            'getContent' => self::VALID_RESPONSE,
            'isOk' => true,
        ));

        $this->client
            ->shouldReceive('getResponse')
            ->andReturn($response)
        ;

        $this->datatable->setQueryParams(array(
            'ccc' => 'CCC',
            'ddd' => 'DDD',
        ));
        $this->datatable->assertResponse($this->filters);
    }

    /**
     * @expectedException PHPUnit_Framework_ExpectationFailedException
     */
    public function testAssertResponseWithFiltersIsNotOk()
    {
        $this->filters
            ->shouldReceive('getQueryString')
            ->once()
            ->andReturn(implode('&', array('aaa', 'bbb')));

        $this->client
            ->shouldReceive('request')
            ->once()
            ->with('GET', DatatableTest::URL . '/test?aaa&bbb');

        $response = \Mockery::mock('Symfony\Component\HttpFoundation\Response', array(
            'getContent' => self::VALID_RESPONSE,
            'isOk' => false,
        ));

        $this->client
            ->shouldReceive('getResponse')
            ->andReturn($response)
        ;

        $this->datatable->assertResponse($this->filters);
    }

    /**
     * @expectedException PHPUnit_Framework_ExpectationFailedException
     */
    public function testAssertResponseWithFiltersInvalid()
    {
        $this->filters
            ->shouldReceive('getQueryString')
            ->once()
            ->andReturn(implode('&', array('aaa', 'bbb')));

        $this->client
            ->shouldReceive('request')
            ->once()
            ->with('GET', DatatableTest::URL . '/test?aaa&bbb');

        $response = \Mockery::mock('Symfony\Component\HttpFoundation\Response', array(
            'getContent' => self::INVALID_RESPONSE,
            'isOk' => true,
        ));

        $this->client
            ->shouldReceive('getResponse')
            ->andReturn($response)
        ;

        $this->datatable->assertResponse($this->filters);
    }

}
