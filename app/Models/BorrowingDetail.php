<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingDetail extends Model
{
    protected $fillable = ['borrowing_id', 'product_id', 'qty', 'item_status'];

public function product() {
    return $this->belongsTo(Product::class);
}
}
