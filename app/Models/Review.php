<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'tutor_request_id',
        'rating',
        'comment',
        'communication_rating',
        'knowledge_rating',
        'punctuality_rating',
        'is_anonymous'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function tutorRequest()
    {
        return $this->belongsTo(TutorRequest::class);
    }
}