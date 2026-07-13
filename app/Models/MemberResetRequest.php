<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberResetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status', // pending, approved, rejected
        'token',
    ];

    /**
     * Get the user associated with the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
