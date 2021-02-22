<?php
namespace App\UseCases;

use App\Clients\StockStatus;
use App\Models\History;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\importantStockUpdate;

class TrackStock
{

protected Stock $stock;
protected StockStatus $status;


public function __construct(Stock $stock)
{
    $this->stock = $stock;
}

    public function handle()
    {
        $this->checkAvailability();
        $this->notifyUser();
        $this->refreshStock();
        $this->recordHistory();
    }

    protected function checkAvailability()
    {
          $this->status = $this->stock
            ->retailer
            ->client()
            ->checkAvailability($this->stock);
    }

    protected function notifyUser()
    {

        if($this->isNowInStock()) {
                User::first()->notify(
                    new importantStockUpdate($this->stock)
                );
        }

    }

    protected function refreshStock()
    {
            $this->stock->update([
                        'in_stock' => $this->status->available,
                        'price' => $this->status->price
                ]);
    }


    protected function recordHistory()
    {

        History::create([

            'price' => $this->stock->price,
            'in_stock' => $this->stock->in_stock,
            'product_id' => $this->stock->product_id,
            'stock_id' => $this->stock->id
        ]);
    }

    protected function isNowInStock(): bool
    {
        return ! $this->stock->in_stock && $this->status->available;
    }
}
