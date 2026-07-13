<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'profile_image',
        'phone',
        'security_question',
        'security_answer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'security_answer',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the member profile associated with the user (if any).
     */
    public function member()
    {
        return $this->hasOne(Member::class);
    }

    /**
     * Get the member reset requests.
     */
    public function memberResetRequests()
    {
        return $this->hasMany(MemberResetRequest::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is member.
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Get user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path($this->avatar))) {
            return asset($this->avatar);
        }
        
        if ($this->profile_image && file_exists(public_path($this->profile_image))) {
            return asset($this->profile_image);
        }
        
        // Default avatar based on name
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=0D8ABC&color=fff&size=100";
    }

    /**
     * Get user's role label.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'member' => 'Member',
            'staff' => 'Staff',
            default => ucfirst($this->role),
        };
    }

    /**
     * Check if user has completed security question.
     */
    public function hasSecurityQuestion(): bool
    {
        return !empty($this->security_question) && !empty($this->security_answer);
    }

    /**
     * Verify security answer.
     */
    public function verifySecurityAnswer(string $answer): bool
    {
        return strtolower(trim($this->security_answer)) === strtolower(trim($answer));
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope a query to only include member users.
     */
    public function scopeMembers($query)
    {
        return $query->where('role', 'member');
    }

    /**
     * Scope a query to only include staff users.
     */
    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }
}