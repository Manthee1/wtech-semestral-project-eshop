<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{


    protected $fillable = ["id", "name", "order_id", 'product_id', "quantity", "unit_price", "street_address", "city", "country", "tracking_number", "status"];

    protected function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected function order()
    {
        return $this->belongsTo(Order::class);
    }
}
