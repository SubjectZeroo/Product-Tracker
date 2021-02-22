<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;


class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all product stock';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Product::all()
            ->tap(fn($products) => $this->output->progressStart($products->count()))
            ->each(function ($product) {
            $product->track();
            $this->output->progressAdvance();
        });

        //*finish the progress bar
        $this->output->progressFinish();

        $this->showResults();

    }

    protected function showResults(): void
    {
        $data =  Product::leftJoin('stock', 'stock.product_id', '=', 'products.id')
        ->get(['name', 'price', 'url', 'in_stock']);


         // ouput the result as a table
         //Name, Price, url, in stock
         $this->table(
             ['Name', 'Price', 'url', 'in Stock'],
             $data
             );
    }
}
