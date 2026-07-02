<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = ['user_id', 'borrow_date', 'return_date', 'status'];

public function user() {
    return $this->belongsTo(User::class);
}
public function details() {
    return $this->hasMany(BorrowingDetail::class);
}
}
