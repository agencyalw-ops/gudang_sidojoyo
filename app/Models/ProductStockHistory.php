<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockHistory extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'qty',
        'before_stock',
        'after_stock',
        'note',
        'user_name'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
