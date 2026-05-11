<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    // Relationships
    public function tutorSubjects()
    {
        return $this->hasMany(TutorSubject::class);
    }

    public function studentWeakSubjects()
    {
        return $this->hasMany(StudentWeakSubject::class);
    }

    public function tutorRequests()
    {
        return $this->hasMany(TutorRequest::class);
    }

    public function recommendations()
    {
        return $this->hasMany(TutorRecommendation::class);
    }
}