<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* =========================================================
     | RELATIONSHIPS
     ==========================================================*/

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_participants')
                    ->withPivot('status', 'registered_at')
                    ->withTimestamps();
    }

    public function scholarships()
    {
        return $this->hasMany(ScholarshipApplication::class);
    }

    public function partnerships()
    {
        return $this->hasMany(PartnershipProposal::class);
    }

    /**
     * ✅ Relationship: User has many achievements
     */
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    /* =========================================================
     | HELPER METHODS
     ==========================================================*/

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAnggota()
    {
        return $this->role === 'anggota';
    }
}
