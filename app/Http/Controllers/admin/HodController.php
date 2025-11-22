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
            abort(403, 'Access denied. HOD only.');
        }
        
        $stats = $this->getDashboardStats($admin->department);
        return view('admin.dashboard-hod', compact('stats'));
    }

    private function getDashboardStats($department)
    {
        $stats = [
            'pending_reviews' => Application::where('department', $department)
                ->where('status', 'academic_approved')
                ->count(),
            'approved_today' => Application::where('department', $department)
                ->where('status', 'final_approved')
                ->whereDate('updated_at', today())
                ->count(),
            'total_approved' => Application::where('department', $department)
                ->where('status', 'final_approved')
                ->count(),
            'total_applications' => Application::where('department', $department)->count(),
            'recent_applications' => Application::where('department', $department)
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
            abort(403, 'Access denied. HOD only.');
        }

        $applications = Application::where('department', $admin->department)
            ->where('status', 'academic_approved')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.applications.hod', compact('applications', 'admin'));
    }

    public function finalApprove($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $application = Application::findOrFail($id);
        
        // Check if application belongs to HOD's department
        if ($application->department !== $admin->department) {
            abort(403, 'Access denied. You can only approve applications from your department.');
        }

        $application->update([
            'status' => 'final_approved'
        ]);

        return redirect()->back()->with('success', 'Application finally approved');
    }

    public function approveFinal($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $application = Application::findOrFail($id);
        
        if ($application->department !== $admin->department) {
            abort(403, 'Access denied. You can only approve applications from your department.');
        }

        $application->update([
            'status' => 'final_approved'
        ]);

        return redirect()->back()->with('success', 'Application finally approved');
    }

    public function myDepartment()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $departmentStats = [
            'total_students' => Application::where('department', $admin->department)
                ->where('status', 'final_approved')
                ->count(),
            'pending_applications' => Application::where('department', $admin->department)
                ->where('status', 'academic_approved')
                ->count(),
            'total_applications' => Application::where('department', $admin->department)->count(),
        ];

        return view('admin.hod.department', compact('departmentStats', 'admin'));
    }

    public function departmentApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $applications = Application::where('department', $admin->department)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.hod.applications', compact('applications', 'admin'));
    }

    // Staff Management Methods
    public function staffIndex()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $staff = Staff::where('department', $admin->department)->get();
        return view('admin.hod.staff', compact('staff', 'admin'));
    }

    public function staffStore(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'position' => 'required|string|max:255',
        ]);

        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'department' => $admin->department,
        ]);

        return redirect()->back()->with('success', 'Staff member added successfully');
    }

    public function staffUpdate(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $staff = Staff::findOrFail($id);
        
        if ($staff->department !== $admin->department) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $id,
            'position' => 'required|string|max:255',
        ]);

        $staff->update($request->all());

        return redirect()->back()->with('success', 'Staff member updated successfully');
    }

    public function staffDestroy($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $staff = Staff::findOrFail($id);
        
        if ($staff->department !== $admin->department) {
            abort(403, 'Access denied.');
            return view('admin.dashboard-hod', compact('stats', 'admin'));
        }

        $staff->delete();

        return redirect()->back()->with('success', 'Staff member deleted successfully');
    }
}