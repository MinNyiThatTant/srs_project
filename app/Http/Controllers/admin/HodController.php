<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HodController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }
        
        $stats = $this->getDashboardStats();
        return view('admin.dashboard-hod', compact('stats'));
    }

    private function getDashboardStats()
    {
        $admin = Auth::guard('admin')->user();
        
        $stats = [
            'department_applications' => Application::where('department', $admin->department)
                ->where('status', 'academic_approved')
                ->count(),
            'department_staff' => Staff::where('department', $admin->department)->count(),
            'approved_today' => Application::where('department', $admin->department)
                ->where('status', 'approved')
                ->whereDate('updated_at', today())
                ->count(),
            'pending_approvals' => Application::where('department', $admin->department)
                ->where('status', 'academic_approved')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];

        return $stats;
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
            'staff_count' => Staff::where('department', $admin->department)->count(),
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

    // Staff Management Methods
    public function staffIndex()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $staff = Staff::where('department', $admin->department)->get();
        return view('hod.staff-management', compact('staff'));
    }

    public function staffStore(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'position' => 'required'
        ]);

        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'department' => $admin->department,
            'position' => $request->position,
            'status' => 'active',
            'created_by' => $admin->id
        ]);

        return redirect()->back()->with('success', 'Staff member added successfully');
    }

    public function staffUpdate(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $staff = Staff::where('department', $admin->department)->findOrFail($id);
        
        $staff->update($request->all());

        return redirect()->back()->with('success', 'Staff member updated successfully');
    }

    public function staffDestroy($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD admin only.');
        }

        $staff = Staff::where('department', $admin->department)->findOrFail($id);
        $staff->delete();

        return redirect()->back()->with('success', 'Staff member deleted successfully');
    }
}