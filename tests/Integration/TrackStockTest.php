<?php

namespace Tests\Integration;

use App\Models\History;
use App\Models\Stock;
use App\Notifications\importantStockUpdate;
use App\UseCases\TrackStock;
use Database\Seeders\RetailerWithProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;


class TracksStockTest extends TestCase
{

    use RefreshDatabase;



    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();

        $this->mockClientRequest($available= true, $price = 24900);

        $this->seed(RetailerWithProduct::class);

        (new TrackStock(Stock::first()))->handle();
    }
    /** @test */
    function test_it_notifies_the_user()
    {

        Notification::assertTimesSent(1, importantStockUpdate::class);

    }

    /** @test */
    function test_it_refreshes_the_local_stock()
    {



        tap(Stock::first(), function($stock) {
            $this->assertEquals(24900, $stock->price);
            $this->assertTrue($stock->in_stock);
        });


    }

    /** @test */
    function test_it_records_to_history()
    {
        $this->assertEquals(1, History::count());
    }

}
