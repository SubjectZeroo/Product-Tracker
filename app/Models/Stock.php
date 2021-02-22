<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Events\NowInStock;
use App\UseCases\TrackStock;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';
    protected $casts =[
        'in_stock' => 'boolean'
    ];


    public function track()
    {
        // (new TrackStock($this))->handle();
        dispatch(new TrackStock($this));
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
