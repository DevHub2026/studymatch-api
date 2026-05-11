<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\TutorRequestController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Tutors
    Route::get('/tutors', [TutorController::class, 'index']);
    Route::get('/tutors/{id}', [TutorController::class, 'show']);
    Route::get('/tutors/{id}/availability', [TutorController::class, 'getAvailability']);
    Route::get('/tutors/search', [TutorController::class, 'search']);
    
    // Tutor Requests
    Route::get('/tutor-requests', [TutorRequestController::class, 'index']);
    Route::post('/tutor-requests/send', [TutorRequestController::class, 'send']);
    Route::post('/tutor-requests/{id}/accept', [TutorRequestController::class, 'accept']);
    Route::post('/tutor-requests/{id}/decline', [TutorRequestController::class, 'decline']);
    Route::post('/tutor-requests/{id}/cancel', [TutorRequestController::class, 'cancel']);
    
    // Subjects
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/weak-subjects', [SubjectController::class, 'getWeakSubjects']);
    Route::post('/weak-subjects', [SubjectController::class, 'addWeakSubject']);
    Route::put('/weak-subjects/{id}', [SubjectController::class, 'updateWeakSubject']);
    Route::delete('/weak-subjects/{id}', [SubjectController::class, 'removeWeakSubject']);
    
    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/my-reviews', [ReviewController::class, 'myReviews']);
    Route::get('/reviews/received', [ReviewController::class, 'received']);
    Route::get('/reviews/tutor/{tutorId}', [ReviewController::class, 'getTutorReviews']);
});