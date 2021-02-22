<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use App\Models\User;

class RetailerWithProduct extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $swicht = Product::create(['name' =>'Nintendo Swicht']);
        $bestBuy = Retailer::create(['name' => 'Best Buy']);


        $bestBuy->addStock($swicht, new Stock([
            'price' => 100000,
            'url' => 'http://foo.com',
            'sku' => '12345',
            'in_stock' => false
        ]));

       $user = User::factory()->create(['email' => 'jasordev@example.com']);
    }
}
