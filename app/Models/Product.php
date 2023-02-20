<?php

namespace App\Models;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function defaultImage()
    {
        return $this->belongsTo(ProductImage::class, 'default_image');
    }

    protected $fillable = ['name', 'description', 'discount', 'price'];
}
