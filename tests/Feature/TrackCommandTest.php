<?php

namespace Tests\Feature;

use Facades\App\Clients\ClientFactory;
use App\Clients\StockStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RetailerWithProduct;
use Illuminate\Support\Facades\Notification;
use App\Notifications\importantStockUpdate;

class TrackCommandTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;



    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->seed(RetailerWithProduct::class);

    }


    /** @test */
    function test_it_tracks_product_stock()
    {


        // Given
        // I have a product with stock


        $this->assertFalse(Product::first()->inStock());

        // Http::fake(fn() => ['onlineAvailability' => true, 'salePrice' => 29000]);
        $this->mockClientRequest();
        // When
        // I trigger the php artisan track command
        // And assuming the stock is available now
        $this->artisan('track');


        // Then
        // the stock details sould be refresehd
        $this->assertTrue(Product::first()->inStock());
    }

     /** @test */
     function test_it_does_not_notify_when_the_stock_remains_unavailable()
     {

         $this->mockClientRequest($available = false);

         // when i track that product
         $this->artisan('track');
         //if the stock changes in a notable way after being tracked
         //then the user should be notified

         Notification::assertNothingSent();
     }



    /** @test */
    function test_it_notifies_the_user_when_the_stock_is_now_available()
    {

        $this->mockClientRequest();
        // when i track that product
        $this->artisan('track');
        //if the stock changes in a notable way after being tracked
        //then the user should be notified

        Notification::assertSentTo(User::first(), importantStockUpdate::class);
    }
}
