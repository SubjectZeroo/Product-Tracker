<?php

namespace Tests\Unit;

use App\Clients\Client;
use App\Clients\ClientException;
use App\Clients\StockStatus;
use Database\Seeders\RetailerWithProduct;
use Tests\TestCase;
use App\Models\Stock;
use App\Models\Retailer;
use Facades\App\Clients\ClientFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class StockTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }


    /** @test */
    function test_it_throws_an_exception_if_a_client_is_not_found_when_tracking()
    {
        // Given i have a retailer with stock

        $this->seed(RetailerWithProduct::class);

        // And if the retailer doesn't have a client class
        Retailer::first()->update(['name' => 'Foo Retailer']);

        // Then an exception should be throw

        $this->expectException(ClientException::class);


        // If i track that stock
        Stock::first()->track();

    }
    /** @test */
    function test_updates_local_stock_status_after_being_tracked()
    {
        $this->seed(RetailerWithProduct::class);

        //clientFactory to determine the appropieate Client
        //checkavailaility
        //['available' => true, 'price' => 9000]


        // ClientFactory::shouldReceive('make->checkAvailability')->andReturn(
        //     new StockStatus($available = true, $price = 9900)
        // );
            $this->mockClientRequest($available = true, $price = 9900);

        $stock = tap(Stock::first())->track();

        $this->assertTrue($stock->in_stock);
        $this->assertEquals(9900, $stock->price);
    }

}

