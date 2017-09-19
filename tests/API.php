<?php
namespace Mindsize\WooCommerce\Test;

use Mindsize\WooCommerce\API;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @property Mockery\MockInterface client
 */
class APITest extends TestCase
{
    /**
     * set up
     */
    public function setUp()
    {
        $this->client = Mockery::mock('Automattic\WooCommerce\Client');
        $this->httpClient = Mockery::mock('Automattic\WooCommerce\HttpClient\HttpClient');
        $this->response = Mockery::mock('Automattic\WooCommerce\HttpClient\response');

        $this->API = new API($this->client);
    }

    public function testSomethingIsTrue()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function post_can_be_called()
    {
        $this->client
            ->shouldReceive('post')
            ->once()
            ->with('someurl',['bar' => 'baz'])
            ->andReturn('foo');

        $this->assertEquals($this->API->post('someurl', ['bar' => 'baz']), 'foo');
    }

    /**
     * @test
     */
    public function put_can_be_called()
    {
        $this->client
            ->shouldReceive('put')
            ->once()
            ->with('someurl',['bar' => 'baz'])
            ->andReturn('foo');

        $this->assertEquals($this->API->put('someurl', ['bar' => 'baz']), 'foo');
    }

    /**
     * @test
     */
    public function get_can_be_called()
    {
        $this->client
            ->shouldReceive('get')
            ->once()
            ->with('someurl',[])
            ->andReturn('foo');

        $this->assertEquals($this->API->get('someurl'), 'foo');
    }

    /**
     * @test
     */
    public function delete_can_be_called()
    {
        $this->client
            ->shouldReceive('delete')
            ->once()
            ->with('someurl',[])
            ->andReturn('foo');

        $this->assertEquals($this->API->delete('someurl'), 'foo');
    }

    /**
     * @test
     */
    public function getrequest_can_be_called()
    {
        $this->httpClient
            ->shouldReceive('getRequest')
            ->once()
            ->andReturn('foo');
        $this->client->http = $this->httpClient;

        $this->assertEquals($this->API->getRequest(), 'foo');
    }

    /**
     * @test
     */
    public function getresponse_can_be_called()
    {
        $this->httpClient
            ->shouldReceive('getResponse')
            ->once()
            ->andReturn('foo');
        $this->client->http = $this->httpClient;

        $this->assertEquals($this->API->getResponse(), 'foo');
    }

    /**
     * @test
     */
    public function pagination_first_page_returns_valid_page_number()
    {
        $this->assertEquals($this->API->firstPage(), 1);
    }

    /**
     * @test
     */
    public function pagination_last_page_returns_valid_page_number()
    {
        $this->httpClient
            ->shouldReceive('getResponse->getHeaders')
            ->once()
            ->andReturn([
                'X-WP-TotalPages' => 12
            ]);
        $this->client->http = $this->httpClient;

        $this->assertEquals($this->API->lastPage(), 12);
    }

    /**
     * @test
     */
    public function pagination_current_page_returns_first_page_when_empty()
    {
        $this->httpClient
            ->shouldReceive('getRequest->getParameters')
            ->once()
            ->andReturn([]);
        $this->client->http = $this->httpClient;

        $this->assertEquals($this->API->currentPage(), 1);
    }

    /**
     * @test
     */
    public function pagination_current_page_returns_valid_page_number()
    {
        $this->httpClient
            ->shouldReceive('getRequest->getParameters')
            ->andReturn([
                'page' => 6
            ]);
        $this->client->http = $this->httpClient;

        $this->assertEquals($this->API->currentPage(), 6);
    }

    /**
     * @test
     */
    public function pagination_total_results_returns_valid_number()
    {
        $this->httpClient
            ->shouldReceive('getResponse->getHeaders')
            ->once()
            ->andReturn([
                'X-WP-Total' => 1234
            ]);
        $this->client->http = $this->httpClient;

        $this->assertEquals($this->API->totalResults(), 1234);
    }

    /**
     * @test
     */
    public function pagination_total_pages_returns_valid_page_number()
    {
        $this->httpClient
            ->shouldReceive('getResponse->getHeaders')
            ->once()
            ->andReturn([
                'X-WP-TotalPages' => 13
            ]);
        $this->client->http = $this->httpClient;

        $this->assertEquals($this->API->lastPage(), 13);
    }

    /**
     * @test
     */
    public function pagination_previous_page_is_null_when_on_first_page()
    {
        $this->httpClient
            ->shouldReceive('getRequest->getParameters')
            ->andReturn([
                'page' => 1
            ]);
        $this->client->http = $this->httpClient;

        $this->assertNull($this->API->previousPage());
        $this->assertFalse($this->API->hasPreviousPage());
        $this->assertTrue($this->API->hasNotPreviousPage());
    }

    /**
     * @test
     */
    public function pagination_previous_page_returns_valid_page_number()
    {
        $this->httpClient
            ->shouldReceive('getRequest->getParameters')
            ->andReturn([
                'page' => 5
            ]);
        $this->client->http = $this->httpClient;

        $this->assertEquals($this->API->previousPage(), 4);
        $this->assertTrue($this->API->hasPreviousPage());
        $this->assertFalse($this->API->hasNotPreviousPage());
    }

    /**
     * @test
     */
    public function pagination_next_page_is_null_when_on_last_page()
    {
        $this->httpClient
            ->shouldReceive('getResponse->getHeaders')
            ->andReturn([
                'X-WP-TotalPages' => 13
            ]);
        $this->httpClient
            ->shouldReceive('getRequest->getParameters')
            ->andReturn([
                'page' => 13
            ]);
        $this->client->http = $this->httpClient;

        $this->assertNull($this->API->nextPage());
        $this->assertFalse($this->API->hasNextPage());
        $this->assertTrue($this->API->hasNotNextPage());
    }

    /**
     * @test
     */
    public function pagination_next_page_returns_valid_page_number()
    {
        $this->httpClient
            ->shouldReceive('getResponse->getHeaders')
            ->andReturn([
                'X-WP-TotalPages' => 13
            ]);
        $this->httpClient
            ->shouldReceive('getRequest->getParameters')
            ->andReturn([
                'page' => 5
            ]);
        $this->client->http = $this->httpClient;

        $this->assertEquals($this->API->nextPage(), 6);
        $this->assertTrue($this->API->hasNextPage());
        $this->assertFalse($this->API->hasNotNextPage());
    }

    /**
     * tear down
     */
    public function tearDown()
    {
        Mockery::close();
    }
}