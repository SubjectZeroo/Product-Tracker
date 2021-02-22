<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;



    /** @test */
    public function test_it_checks_stock_for_products_at_retailers()
    {
        $swicht = Product::create(['name' =>'Nintendo Swicht']);
        $bestBuy = Retailer::create(['name' => 'Best Buy']);

        $this->assertFalse($swicht->inStock());

        $stock = new Stock([
            'price' => 100000,
            'url' => 'http://foo.com',
            'sku' => '63544253',
            'in_stock' => true
        ]);

        $bestBuy->addStock($swicht, $stock);

        $this->assertTrue($swicht->inStock());
    }
}
