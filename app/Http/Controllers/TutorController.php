<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Subject;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    /**
     * Browse all tutors with filters
     */
    public function index(Request $request)
    {
        $query = Tutor::with(['user', 'strongSubjects.subject', 'availability'])
            ->verified()
            ->available();

        // Filter by subject
        if ($request->subject_id) {
            $query->whereHas('strongSubjects', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        // Filter by tutor type
        if ($request->tutor_type) {
            $query->where('tutor_type', $request->tutor_type);
        }

        // Filter by minimum rating
        if ($request->min_rating) {
            $query->where('average_rating', '>=', $request->min_rating);
        }

        // Filter by maximum hourly rate
        if ($request->max_rate) {
            $query->where('hourly_rate', '<=', $request->max_rate);
        }

        $tutors = $query->paginate(20);

        return response()->json($tutors);
    }

    /**
     * Get specific tutor profile
     */
    public function show($id)
    {
        $tutor = Tutor::with([
            'user',
            'strongSubjects.subject',
            'availability',
            'reviews.student.user'
        ])->findOrFail($id);

        return response()->json($tutor);
    }

    /**
     * Get tutor availability
     */
    public function getAvailability($id)
    {
        $tutor = Tutor::findOrFail($id);
        $availability = $tutor->availability()->where('is_active', true)->get();

        return response()->json($availability);
    }

    /**
     * Search tutors
     */
    public function search(Request $request)
    {
        $query = $request->q;

        $tutors = Tutor::with(['user', 'strongSubjects.subject'])
            ->verified()
            ->available()
            ->whereHas('user', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('strongSubjects.subject', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($tutors);
    }
}