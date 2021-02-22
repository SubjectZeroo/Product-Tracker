<?php

namespace App\Clients;
use Illuminate\Support\Facades\Http;
use App\Models\Stock;

class BestBuy implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {


        $results =  Http::get($this->endpoint($stock->sku))->json();

        return new StockStatus(
            $results['onlineAvailability'],
            $this->dollarsTocents($results['salePrice'])

        );
    }

    protected function endpoint($sku): string
    {
        $key = config('services.clients.bestbuy.key');
        return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";

    }

    protected function dollarsTocents($salePrice)
    {
        return (int) ($salePrice * 100);
    }
}
