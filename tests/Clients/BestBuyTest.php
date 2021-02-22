<?php
namespace Tests\Clients;

use App\Clients\BestBuy;
use Tests\TestCase;
use App\Models\Stock;
use Database\Seeders\RetailerWithProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

 class BestBuyTest extends TestCase
    {
        use RefreshDatabase;

        /** @test */
        function test_it_tracks_a_product()
        {
            //given i have a product
            $this->seed(RetailerWithProduct::class);

            //with stock at bestbuy

            $stock= tap(Stock::first())->update([
                'sku' => '6409059',
                'url' => 'https://www.bestbuy.com/site/msi-ge66-10sfs-15-6-gaming-laptop-intel-core-i7-32gb-memory-nvidia-geforce-rtx-2070-super-1tb-ssd-aluminum-black/6409059.p?skuId=6409059'
            ]);


            //if use te bestuy client to track that stock/sku

            try {
             (new BestBuy())->checkAvailability($stock);

            } catch (\Exception $e) {
                $this->fail('Failed to track the besbuy api properly.'. $e->getMessage());
            }

            $this->assertTrue(true);

        }


        /** @test */
        function it_creates_the_proper_stock_status_response()
        {
            Http::fake(fn() => ['salePrice' => 299.99, 'onlineAvailability' => true]);
            $stockStatus = (new BestBuy())->checkAvailability(new Stock);

            $this->assertEquals(29999, $stockStatus->price);
            $this->assertTrue($stockStatus->available);
        }
    }
