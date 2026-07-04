<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'borrow_date',
        'due_date',
        'return_date',
        'purpose',
        'status',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(BorrowingDetail::class, 'borrowing_id');
    }

    /**
     * Cek apakah peminjaman ini sudah lewat batas waktu (overdue).
     * Hanya relevan kalau statusnya masih 'borrowed'.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'borrowed'
            && $this->due_date
            && now()->startOfDay()->gt($this->due_date);
    }
}