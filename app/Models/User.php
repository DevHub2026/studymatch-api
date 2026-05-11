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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    // Helper methods
    public function isStudent()
    {
        return $this->student()->exists();
    }

    public function isTutor()
    {
        return $this->tutor()->exists() && $this->tutor->verification_status === 'approved';
    }

    public function isAdmin()
    {
        // You can add an 'is_admin' field to users table later
        return $this->email === 'admin@rmmc.edu'; // Temporary check
    }
}