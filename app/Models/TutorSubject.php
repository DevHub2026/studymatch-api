<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'subject_id',
        'expertise_level',
        'grade_achieved',
        'years_teaching',
        'is_primary_expertise'
    ];

    protected $casts = [
        'grade_achieved' => 'decimal:2',
        'is_primary_expertise' => 'boolean'
    ];

    // Relationships
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}