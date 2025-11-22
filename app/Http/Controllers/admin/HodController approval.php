<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Admin;
use App\Services\StudentApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HodController extends Controller
{
    private $approvalService;

    public function __construct(StudentApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    public function finalApprove($id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $application = Application::findOrFail($id);

        // Check if application belongs to HOD's department
        if ($application->department !== $admin->department) {
            abort(403, 'Access denied. Application not in your department.');
        }

        // Check if application is ready for final approval
        if ($application->status !== Application::STATUS_HOD_APPROVED) {
            return redirect()->back()->with('error', 'Application must be HOD approved first.');
        }

        try {
            // Final approval - this will generate student credentials
            $credentials = $application->markAsFinalApproved($admin->id);

            return redirect()->back()->with(
                'success',
                "Application finally approved! Student ID: {$credentials['student_id']}, Password: {$credentials['password']}"
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }
}
