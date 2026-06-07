<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;

class AdminAssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::with('user')->latest()->paginate(15);

        return view('admin.assessment.index', compact('assessments'));
    }
}
