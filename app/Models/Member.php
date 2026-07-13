<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_code',
        'total_loans',
        'points',
        'borrow_limit',
        'is_verified',
        'status',
        'registered_at',
    ];

    protected $casts = [
        'total_loans' => 'integer',
        'points' => 'integer',
        'borrow_limit' => 'integer',
        'is_verified' => 'boolean',
        'registered_at' => 'datetime',
    ];

    /**
     * Get the user account for this member.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrowings for this member.
     */
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * Get membership level details dynamically.
     */
    public function getMembershipDetailsAttribute()
    {
        $loans = $this->total_loans;

        if ($loans >= 30) {
            return [
                'name' => 'Gold Member',
                'card_bg' => 'linear-gradient(135deg, #4d3a12 0%, #151003 100%)',
                'badge_bg' => 'linear-gradient(135deg, #F5B025 0%, #a37b17 100%)',
                'badge_color' => '#FFFFFF',
            ];
        } elseif ($loans >= 11) {
            return [
                'name' => 'Silver Member',
                'card_bg' => 'linear-gradient(135deg, #2d3340 0%, #0f121a 100%)',
                'badge_bg' => 'linear-gradient(135deg, #c0c0c0 0%, #7f7f7f 100%)',
                'badge_color' => '#FFFFFF',
            ];
        } else {
            return [
                'name' => 'Bronze Member',
                'card_bg' => 'linear-gradient(135deg, #3d2d1e 0%, #130e09 100%)',
                'badge_bg' => 'linear-gradient(135deg, #cd7f32 0%, #8c531d 100%)',
                'badge_color' => '#FFFFFF',
            ];
        }
    }

    /**
     * Scope a query to only include verified members.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to only include pending members.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include active members.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if member is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if member is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if member is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if member is verified.
     */
    public function isVerified(): bool
    {
        return $this->is_verified === true;
    }

    /**
     * Get borrow limit remaining.
     */
    public function getBorrowLimitRemainingAttribute(): int
    {
        $currentBorrows = $this->borrows()
            ->where('status', 'borrowed')
            ->count();
        
        return max(0, $this->borrow_limit - $currentBorrows);
    }

    /**
     * Check if member can borrow more books.
     */
    public function canBorrow(): bool
    {
        return $this->isActive() && 
               $this->isVerified() && 
               $this->getBorrowLimitRemainingAttribute() > 0;
    }
}