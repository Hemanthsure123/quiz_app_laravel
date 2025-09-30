<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use Illuminate\Support\Facades\Session;

class ExamController extends Controller
{
    // Show create exam form

    // List all exams in admin dashboard
    public function index()
    {
        $exams = Exam::all();  // Fetch all exams from database
        return view('admin.dashboard', ['exams' => $exams]);  // Pass to view
    }
    public function create()
    {
        return view('admin.exams.create');  // We'll create this view
    }

    // Store new exam and generate link
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'duration' => 'required|integer|min:1',  // Ensure it's a positive number
    ]);

    $exam = Exam::create([
        'title' => $request->title,
        'description' => $request->description,
        'duration' => $request->duration,
    ]);

    $link = route('exam.register', ['uuid' => $exam->uuid]);
    return redirect()->route('admin.dashboard')->with('success', 'Exam created! Link: ' . $link);
}
}