<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HodController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        $department = $admin->department;

        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $stats = [
            'department_applications' => Application::where('department', $department)->count(),
            'pending_applications' => Application::where('department', $department)
                ->where('status', 'pending')
                ->count(),
            'approved_applications' => Application::where('department', $department)
                ->where('status', 'approved')
                ->count(),
            'department_staff' => Admin::where('department', $department)
                ->where('role', '!=', 'hod_admin')
                ->count(),
            'active_staff' => Admin::where('department', $department)
                ->where('role', '!=', 'hod_admin')
                ->count(), // Removed is_active condition
            'department_courses' => 0,
            'pending_applications_list' => Application::where('department', $department)
                ->where('status', 'academic_approved')
                ->latest()
                ->take(5)
                ->get()
        ];

        return view('admin.dashboard-hod', compact('stats'));
    }

    public function hodApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $applications = Application::where('department', $admin->department)
            ->where('status', 'academic_approved')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.applications.hod', compact('applications'));
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

        $application->update([
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', 'Application finally approved');
    }

    public function myDepartment()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $departmentInfo = [
            'name' => $admin->department,
            'staff_count' => Admin::where('department', $admin->department)
                ->where('role', '!=', 'hod_admin')
                ->count(),
            'applications_count' => Application::where('department', $admin->department)->count(),
            'pending_approvals' => Application::where('department', $admin->department)
                ->where('status', 'academic_approved')
                ->count()
        ];

        return view('admin.department.info', compact('departmentInfo'));
    }

    public function departmentApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $applications = Application::where('department', $admin->department)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.department.applications', compact('applications'));
    }

    public function approveFinal($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $application = Application::findOrFail($id);
        
        if ($application->department !== $admin->department) {
            abort(403, 'Access denied. Application not in your department.');
        }

        $application->update([
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', 'Application finally approved');
    }

    // Staff Management Methods - Using Admin model
    public function staffIndex()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $staff = Admin::where('department', $admin->department)
            ->where('role', '!=', 'hod_admin')
            ->get();
            
        return view('admin.staff.index', compact('staff'));
    }

    public function staffStore(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'position' => 'required',
            'role' => 'required|in:teacher_admin,haa_admin,fa_admin'
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password123'), // Default password
            'department' => $admin->department,
            'role' => $request->role,
            'position' => $request->position,
            // Removed is_active as it doesn't exist in your table
        ]);

        return redirect()->back()->with('success', 'Staff member added successfully');
    }

    public function staffUpdate(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $staff = Admin::where('department', $admin->department)
            ->where('id', $id)
            ->firstOrFail();
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $id,
            'position' => 'required',
            'role' => 'required|in:teacher_admin,haa_admin,fa_admin'
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'role' => $request->role,
            // Removed is_active as it doesn't exist in your table
        ]);

        return redirect()->back()->with('success', 'Staff member updated successfully');
    }

    public function staffDestroy($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $staff = Admin::where('department', $admin->department)
            ->where('id', $id)
            ->firstOrFail();
            
        $staff->delete();

        return redirect()->back()->with('success', 'Staff member deleted successfully');
    }
}