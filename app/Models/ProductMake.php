<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMake extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name',
    ];

    public function models()
    {
        return $this->hasMany(ProductModel::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
