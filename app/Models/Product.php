<?php

namespace App\Models;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $appends = ['discounted_price'];
    protected $with = ['default_image'];
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function default_image()
    {
        return $this->belongsTo(ProductImage::class, 'default_image');
    }

    public function getDiscountedPriceAttribute()
    {
        $discountedPrice = $this->price - ($this->price * $this->discount) / 100;
        return round($discountedPrice, 2);
    }

    protected $fillable = ['name', 'description', 'discount', 'price'];
}
