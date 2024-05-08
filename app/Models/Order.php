<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{


    protected $fillable = ["id", "name", "total", "street_address", "city", "country", "tracking_number", "status", "user_id"];

    protected function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedTotal()
    {
        return formatPrice($this->total);
    }
}
