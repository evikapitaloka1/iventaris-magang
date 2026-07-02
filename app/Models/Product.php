<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'product_code', 'name', 'stock', 'location', 'condition', 'image_path'];

public function category() {
    return $this->belongsTo(Category::class);
}
}
