<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentWeakSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'difficulty_level',
        'current_grade',
        'needs_help',
        'notes'
    ];

    protected $casts = [
        'current_grade' => 'decimal:2',
        'needs_help' => 'boolean'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}