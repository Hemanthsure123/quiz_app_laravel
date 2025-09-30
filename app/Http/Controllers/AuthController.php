<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show the registration form
    public function showRegisterForm()
    {
        return view('register');  // We'll create this view
    }

    // Handle registration and send OTP
    public function register(Request $request)
    {
        // Validate input (check if fields are filled correctly)
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'city' => 'required|string|max:255',
            'contact_number' => 'required|string|max:15',
        ]);

        // Create a new user but don't log in yet
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'city' => $request->city,
            'contact_number' => $request->contact_number,
            // Add a dummy password since we don't need it for OTP
            'password' => bcrypt('dummy_password'),
        ]);

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP and user ID in session (temporary storage)
        Session::put('otp', $otp);
        Session::put('user_id', $user->id);

        // Send OTP via email
        $userDetails = [
            'username' => $request->full_name,
            'email' => $request->email,
            'city' => $request->city,
            'mobile_number' => $request->contact_number,
            'otp' => $otp,
        ];

        // Send the welcome email with details
        Mail::to($request->email)->send(new \App\Mail\WelcomeEmail($userDetails));

        // Redirect to OTP verification page
        return redirect()->route('verify-otp')->with('success', 'OTP sent to your email!');
    }

    // Show OTP verification form
    public function showVerifyForm()
    {
        return view('verify-otp');  // We'll create this view
    }

    // Verify the OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $storedOtp = Session::get('otp');

        if ($request->otp == $storedOtp) {
            $userId = Session::get('user_id');
            $user = User::find($userId);
            Auth::login($user);

            // Clear OTP and user_id, but keep exam_id
            Session::forget(['otp', 'user_id']);

            return redirect()->route('dashboard')->with('success', 'Verified! Start your exam.');
        } else {
            return back()->with('error', 'Invalid OTP. Try again.');
        }
    }

    // Show registration form with exam UUID
    public function showRegisterFormWithExam($uuid)
    {
        $exam = \App\Models\Exam::where('uuid', $uuid)->firstOrFail();
        Session::put('exam_id', $exam->id);  // Store exam ID in session for later
        return view('register');  // Same register view as before
    }

    // Dashboard page after login
        public function dashboard()
    {
        $examId = Session::get('exam_id');
        if (!$examId) {
            return redirect('/')->with('error', 'No exam selected.');
        }

        $exam = \App\Models\Exam::findOrFail($examId);
        return view('dashboard', ['exam' => $exam]);  // Pass exam to view
    }
}