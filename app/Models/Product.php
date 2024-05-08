<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'price',
        'description',
        'stock',
        'make_id',
        'make_id',
        'drivetrain_id',
        'body_type_id',
        'efficiency',
        'engine_type_id',
        'height',
        'width',
        'length',
        'model_id',
        'model_id',
        'horse_power',
        'passenger_capacity',
        'year',
        'active',
    ];


    public function getName()
    {
        return $this->make->name . ' ' . $this->model->name . ' ' . $this->year;
    }

    public function getSlug()
    {
        return $this->make->name . '-' . $this->model->name . '-' . $this->year . '-' . $this->body_type . '-' . $this->id;
    }

    public function getFormattedPrice()
    {
        return formatPrice($this->price);
    }

    public function make()
    {
        return $this->belongsTo(ProductMake::class);
    }

    public function model()
    {
        return $this->belongsTo(ProductModel::class);
    }

    public function drivetrain()
    {
        return $this->belongsTo(ProductDrivetrain::class);
    }

    public function bodyType()
    {
        return $this->belongsTo(ProductBodyType::class);
    }

    public function engineType()
    {
        return $this->belongsTo(ProductEngineType::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort');
    }

    public function getMainImageUrl()
    {
        return env("APP_PRODUCTS_IMAGES_PATH") . '/' . $this->images->first()->filename;
    }
}
