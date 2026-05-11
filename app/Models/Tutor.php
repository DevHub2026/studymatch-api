<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'position',
        'tutor_type',
        'specialization',
        'hourly_rate',
        'bio',
        'credentials',
        'verification_documents',
        'verification_status',
        'verified_at',
        'verified_by',
        'average_rating',
        'total_sessions',
        'total_earnings',
        'is_available',
        'is_deans_list',
        'gpa'
    ];

    protected $casts = [
        'verification_documents' => 'array',
        'verified_at' => 'datetime',
        'average_rating' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'gpa' => 'decimal:2',
        'is_available' => 'boolean',
        'is_deans_list' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function strongSubjects()
    {
        return $this->hasMany(TutorSubject::class);
    }

    public function availability()
    {
        return $this->hasMany(TutorAvailability::class);
    }

    public function tutorRequests()
    {
        return $this->hasMany(TutorRequest::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function recommendations()
    {
        return $this->hasMany(TutorRecommendation::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'approved');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}