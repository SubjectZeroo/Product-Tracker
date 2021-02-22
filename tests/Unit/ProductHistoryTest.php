<?php

namespace Tests\Unit;

use Facades\App\Clients\ClientFactory;
use App\Clients\StockStatus;
use Database\Seeders\RetailerWithProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use App\Models\History;
use App\Models\Product;

class ProductHistoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    function test_records_history_each_time_stock_is_track()
    {
        //given i have stock at a retailer
        $this->seed(RetailerWithProduct::class);

        $this->mockClientRequest($available = true, $price = 9900);

       $product = tap(Product::first(), function($product) {
            $this->assertCount(0, $product->history);
            // If i track that stock
            $product->track();
            // A new history entry should be created
            $this->assertCount(1, $product->refresh()->history);
        });

        $history = $product->history->first();
        $this->assertEquals($price, $history->price);
        $this->assertEquals($available, $history->in_stock);
        $this->assertEquals($product->stock[0]->id, $history->stock_id);
    }
}
