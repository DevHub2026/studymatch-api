<?php

namespace App\Http\Controllers;

use App\Models\TutorRequest;
use Illuminate\Http\Request;

class TutorRequestController extends Controller
{
    /**
     * Get all requests (sent + received)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $sentRequests = [];
        $receivedRequests = [];

        // If user is a student, get sent requests
        if ($user->student) {
            $sentRequests = TutorRequest::with(['tutor.user', 'subject'])
                ->where('student_id', $user->student->id)
                ->latest()
                ->get();
        }

        // If user is a tutor, get received requests
        if ($user->tutor) {
            $receivedRequests = TutorRequest::with(['student.user', 'subject'])
                ->where('tutor_id', $user->tutor->id)
                ->latest()
                ->get();
        }

        return response()->json([
            'sent' => $sentRequests,
            'received' => $receivedRequests
        ]);
    }

    /**
     * Send tutor request
     */
    public function send(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutors,id',
            'subject_id' => 'required|exists:subjects,id',
            'message' => 'nullable|string'
        ]);

        $student = $request->user()->student;

        if (!$student) {
            return response()->json([
                'message' => 'Only students can send tutor requests'
            ], 403);
        }

        // Check if request already exists
        $existing = TutorRequest::where('student_id', $student->id)
            ->where('tutor_id', $request->tutor_id)
            ->where('subject_id', $request->subject_id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You already have a pending request to this tutor for this subject'
            ], 400);
        }

        $tutorRequest = TutorRequest::create([
            'student_id' => $student->id,
            'tutor_id' => $request->tutor_id,
            'subject_id' => $request->subject_id,
            'message' => $request->message,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Tutor request sent successfully',
            'request' => $tutorRequest->load(['tutor.user', 'subject'])
        ], 201);
    }

    /**
     * Accept tutor request
     */
    public function accept($id)
    {
        $tutorRequest = TutorRequest::findOrFail($id);

        // Verify the tutor owns this request
        if ($tutorRequest->tutor_id !== auth()->user()->tutor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tutorRequest->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        return response()->json([
            'message' => 'Request accepted',
            'request' => $tutorRequest
        ]);
    }

    /**
     * Decline tutor request
     */
    public function decline($id)
    {
        $tutorRequest = TutorRequest::findOrFail($id);

        // Verify the tutor owns this request
        if ($tutorRequest->tutor_id !== auth()->user()->tutor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tutorRequest->update([
            'status' => 'declined',
            'declined_at' => now()
        ]);

        return response()->json([
            'message' => 'Request declined',
            'request' => $tutorRequest
        ]);
    }

    /**
     * Cancel sent request
     */
    public function cancel($id)
    {
        $tutorRequest = TutorRequest::findOrFail($id);

        // Verify the student owns this request
        if ($tutorRequest->student_id !== auth()->user()->student->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tutorRequest->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'message' => 'Request cancelled',
            'request' => $tutorRequest
        ]);
    }
}