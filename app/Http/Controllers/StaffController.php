<?php

namespace App\Http\Controllers;

use App\Services\EmployeeManagement\Staff;

class StaffController extends Controller
{
    public function __construct(private readonly Staff $staff)
    {
    }
    
    public function payroll()
    {
        $data = $this->staff->salary();  

        if ($data) { # If payroll check is success, return success msg and data
            return response()->json([
                'message' => 'Payroll retrieved successfully.',
                'data' => $data,
            ], 200);
        }
        # If payroll check is failed, return error msg and data
        return response()->json([
            'message' => 'Failed to retrieve payroll data.',
        ], 400);
    }
}
