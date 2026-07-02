<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'title',
        'author',
        'publisher',
        'year',
        'category',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'year' => 'integer',
    ];

    /**
     * Get the borrowings for this book.
     */
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
