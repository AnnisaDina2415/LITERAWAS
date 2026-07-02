<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Get the member who borrowed.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the book that was borrowed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
