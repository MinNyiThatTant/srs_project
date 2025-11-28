<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.applications.all');
    }

    public function allApplications(Request $request)
    {
        $query = Application::query();
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('application_id', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }
        
        $applications = $query->orderBy('created_at', 'desc')->paginate(25);
        
        // Statistics
        $stats = [
            'total' => Application::count(),
            'payment_pending' => Application::where('status', Application::STATUS_PAYMENT_PENDING)->count(),
            'payment_verified' => Application::where('status', Application::STATUS_PAYMENT_VERIFIED)->count(),
            'academic_approved' => Application::where('status', Application::STATUS_ACADEMIC_APPROVED)->count(),
            'approved' => Application::where('status', Application::STATUS_FINAL_APPROVED)->count(),
            'rejected' => Application::where('status', Application::STATUS_REJECTED)->count(),
        ];
        
        return view('admin.applications.all', compact('applications', 'stats'));
    }

    public function academicApplications()
    {
        $applications = Application::where('status', Application::STATUS_PAYMENT_VERIFIED)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.applications.academic', compact('applications'));
    }

    public function financeApplications()
    {
        $applications = Application::where('status', Application::STATUS_PAYMENT_PENDING)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.applications.finance', compact('applications'));
    }

    public function hodApplications()
    {
        $userDepartment = auth()->guard('admin')->user()->department;
        
        $applications = Application::where('status', Application::STATUS_ACADEMIC_APPROVED)
            ->where('department', $userDepartment)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.applications.hod', compact('applications'));
    }

    public function viewApplication($id)
    {
        $application = Application::findOrFail($id);
        return view('admin.applications.view', compact('application'));
    }

    public function verifyPayment(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->markPaymentAsVerified(auth()->guard('admin')->id());
        
        return redirect()->back()->with('success', 'Payment verified successfully!');
    }

    public function academicApprove(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->markAsAcademicApproved(auth()->guard('admin')->id());
        
        return redirect()->back()->with('success', 'Application academically approved!');
    }

    public function academicReject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10'
        ]);

        $application = Application::findOrFail($id);
        $application->markAsRejected($request->rejection_reason, auth()->guard('admin')->id());

        return redirect()->back()->with('success', 'Application rejected!');
    }

    public function finalApprove(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $credentials = $application->markAsFinalApproved(auth()->guard('admin')->id());

        return redirect()->back()->with('success', 
            "Application finally approved! Student ID: {$credentials['student_id']}");
    }
}