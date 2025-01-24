<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmployeeManagement\Applicant;

class JobController extends Controller
{
    public function __construct(private readonly Applicant $applicant)
    {
    }
    
    public function apply(Request $request)
    {
        $data = $this->applicant->applyJob();
        if ($data) { # If apply job is success, return success msg and data 
            return response()->json([
                'message' => 'Job application submitted successfully.',
                'data' => $data,
            ], 200);
        } 
        # If apply job is failed, return error msg
        return response()->json([
            'message' => 'Failed to submit the job application!',
        ], 400);
        
    }
}
