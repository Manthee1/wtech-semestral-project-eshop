<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'filename',
        'sort',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrl()
    {
        return env("APP_PRODUCTS_IMAGES_PATH") . '/' . $this->filename;
    }
}
