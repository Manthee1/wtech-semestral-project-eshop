<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'make_id',
        'name',
    ];

    public function make()
    {
        return $this->belongsTo(ProductMake::class);
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
