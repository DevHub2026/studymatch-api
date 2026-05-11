<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'program',
        'year_level',
        'bio'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function weakSubjects()
    {
        return $this->hasMany(StudentWeakSubject::class);
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
}