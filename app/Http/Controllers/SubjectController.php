<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\StudentWeakSubject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Get all subjects
     */
    public function index()
    {
        $subjects = Subject::all();
        return response()->json($subjects);
    }

    /**
     * Get my weak subjects
     */
    public function getWeakSubjects(Request $request)
    {
        $student = $request->user()->student;

        if (!$student) {
            return response()->json([
                'message' => 'Only students can have weak subjects'
            ], 403);
        }

        $weakSubjects = StudentWeakSubject::with('subject')
            ->where('student_id', $student->id)
            ->get();

        return response()->json($weakSubjects);
    }

    /**
     * Add a weak subject
     */
    public function addWeakSubject(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'difficulty_level' => 'required|in:moderate,difficult,very_difficult',
            'current_grade' => 'nullable|numeric|min:1.0|max:5.0',
            'notes' => 'nullable|string'
        ]);

        $student = $request->user()->student;

        if (!$student) {
            return response()->json([
                'message' => 'Only students can add weak subjects'
            ], 403);
        }

        // Check if already exists
        $existing = StudentWeakSubject::where('student_id', $student->id)
            ->where('subject_id', $request->subject_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'This subject is already in your weak subjects list'
            ], 400);
        }

        $weakSubject = StudentWeakSubject::create([
            'student_id' => $student->id,
            'subject_id' => $request->subject_id,
            'difficulty_level' => $request->difficulty_level,
            'current_grade' => $request->current_grade,
            'notes' => $request->notes,
            'needs_help' => true
        ]);

        return response()->json([
            'message' => 'Weak subject added successfully',
            'weak_subject' => $weakSubject->load('subject')
        ], 201);
    }

    /**
     * Remove a weak subject
     */
    public function removeWeakSubject($id)
    {
        $weakSubject = StudentWeakSubject::findOrFail($id);

        // Verify ownership
        if ($weakSubject->student_id !== auth()->user()->student->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $weakSubject->delete();

        return response()->json([
            'message' => 'Weak subject removed successfully'
        ]);
    }

    /**
     * Update weak subject
     */
    public function updateWeakSubject(Request $request, $id)
    {
        $request->validate([
            'difficulty_level' => 'sometimes|in:moderate,difficult,very_difficult',
            'current_grade' => 'nullable|numeric|min:1.0|max:5.0',
            'notes' => 'nullable|string',
            'needs_help' => 'sometimes|boolean'
        ]);

        $weakSubject = StudentWeakSubject::findOrFail($id);

        // Verify ownership
        if ($weakSubject->student_id !== auth()->user()->student->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $weakSubject->update($request->all());

        return response()->json([
            'message' => 'Weak subject updated successfully',
            'weak_subject' => $weakSubject->load('subject')
        ]);
    }
}