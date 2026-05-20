<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create student profile automatically
        Student::create([
            'user_id' => $user->id,
        ]);

        // Generate and cache a 6-digit OTP for 10 minutes
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $user->email, $otp, now()->addMinutes(10));

        // Send OTP email
        Mail::raw("Your StudyMatch verification code is: $otp\n\nThis code expires in 10 minutes.", function ($m) use ($user) {
            $m->to($user->email)
              ->subject('StudyMatch - Email Verification Code');
        });

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'    => $user,
            'token'   => $token,
            'message' => 'Registration successful. Please check your email for the verification code.',
        ], 201);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = $request->user();
        $cachedOtp = Cache::get('otp_' . $user->email);

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return response()->json([
                'message' => 'Invalid or expired verification code.',
            ], 422);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        // Clear OTP from cache
        Cache::forget('otp_' . $user->email);

        return response()->json([
            'message' => 'Email verified successfully.',
        ]);
    }

    public function resendVerification(Request $request)
    {
        $user = $request->user();

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email is already verified.',
            ], 422);
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $user->email, $otp, now()->addMinutes(10));

        Mail::raw("Your StudyMatch verification code is: $otp\n\nThis code expires in 10 minutes.", function ($m) use ($user) {
            $m->to($user->email)
              ->subject('StudyMatch - Email Verification Code');
        });

        return response()->json([
            'message' => 'Verification code resent successfully.',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load(['student', 'tutor']),
        ]);
    }
}