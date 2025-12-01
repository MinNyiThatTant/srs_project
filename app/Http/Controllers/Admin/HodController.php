<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HodController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }
        
        $department = $this->getHodDepartment($admin);
        $stats = $this->getDashboardStats($department);
        $departmentInfo = $this->getDepartmentInfo($department);
=======
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }
        
        $stats = $this->getDashboardStats($admin->department);
        $departmentInfo = $this->getDepartmentInfo($admin->department);
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        
        return view('admin.hod.dashboard-hod', compact('stats', 'admin', 'departmentInfo'));
    }

<<<<<<< HEAD
    /**
     * Check if user is HOD admin and get their department
     */
    private function isHodAdmin($admin)
    {
        return $admin->role === 'hod_admin';
    }

    /**
     * Get HOD's department from admin record
     */
    private function getHodDepartment($admin)
    {
        return $admin->department;
    }

    private function getDashboardStats($department)
    {
        $stats = [
            'pending_reviews' => Application::where('assigned_department', $department)
                ->where('status', 'academic_approved')
                ->count(),
            'approved_today' => Application::where('assigned_department', $department)
                ->where('status', 'approved')
                ->whereDate('final_approved_at', today())
                ->count(),
            'total_approved' => Application::where('assigned_department', $department)
                ->where('status', 'approved')
                ->count(),
            'total_applications' => Application::where('assigned_department', $department)->count(),
            'active_students' => Student::where('department', $department)
                ->where('status', 'active')
                ->count(),
            'recent_applications' => Application::where('assigned_department', $department)
=======
    private function getDashboardStats($department)
    {
        $stats = [
            'pending_reviews' => Application::where('department', $department)
                ->where('status', 'academic_approved')
                ->count(),
            'approved_today' => Application::where('department', $department)
                ->where('status', 'approved')
                ->whereDate('final_approved_at', today())
                ->count(),
            'total_approved' => Application::where('department', $department)
                ->where('status', 'approved')
                ->count(),
            'total_applications' => Application::where('department', $department)->count(),
            'active_students' => Student::where('department', $department)
                ->where('status', 'active')
                ->count(),
            'recent_applications' => Application::where('department', $department)
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
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
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }

        $department = $this->getHodDepartment($admin);
        $applications = Application::where('assigned_department', $department)
=======
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $applications = Application::where('department', $admin->department)
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            ->where('status', 'academic_approved')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

<<<<<<< HEAD
        $departmentInfo = $this->getDepartmentInfo($department);
=======
        $departmentInfo = $this->getDepartmentInfo($admin->department);
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c

        return view('admin.hod.applications', compact('applications', 'admin', 'departmentInfo'));
    }

    private function getDepartmentInfo($department)
    {
        $departments = [
            'Computer Engineering and Information Technology' => [
                'name' => 'Computer Engineering and Information Technology',
                'code' => 'CEIT',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာမောင်မောင်',
                'email' => 'hod.ceit@admin.com',
=======
                'head' => 'Dr. John Doe',
                'email' => 'hod.ceit@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234567',
                'location' => 'IT Building, Room 101',
                'description' => 'Department of Computer Engineering and Information Technology focuses on software development, networking, and computer systems.',
                'established' => 2005,
                'total_staff' => Staff::where('department', 'Computer Engineering and Information Technology')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Computer Engineering and Information Technology')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Computer Engineering and Information Technology')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Civil Engineering' => [
                'name' => 'Civil Engineering',
                'code' => 'CIVIL',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာအောင်ထွန်း',
                'email' => 'hod.civil@admin.com',
=======
                'head' => 'Dr. Jane Smith',
                'email' => 'hod.civil@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234568',
                'location' => 'Engineering Building, Room 201',
                'description' => 'Department of Civil Engineering specializing in structural design and construction management.',
                'established' => 1999,
                'total_staff' => Staff::where('department', 'Civil Engineering')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Civil Engineering')
                    ->where('status', 'active')
                    ->count()
            ],
            'Electronic Engineering' => [ // CHANGED FROM "Electronics Engineering"
                'name' => 'Electronic Engineering',
                'code' => 'EE',
                'head' => 'ဒေါက်တာဆာမွန်လီ',
                'email' => 'hod.electronics@admin.com',
                'phone' => '+95 1 234570',
                'location' => 'Electronics Building, Room 401',
                'description' => 'Department of Electronic Engineering specializing in circuit design and embedded systems.',
                'established' => 2001,
                'total_staff' => Staff::where('department', 'Electronic Engineering')->count(),
                'active_students' => Student::where('department', 'Electronic Engineering')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Civil Engineering')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Electrical Power Engineering' => [
                'name' => 'Electrical Power Engineering',
                'code' => 'EPE',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာဂျွန်မောင်',
                'email' => 'hod.electrical@admin.com',
=======
                'head' => 'Dr. Robert Brown',
                'email' => 'hod.electrical@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234569',
                'location' => 'Power Engineering Building, Room 301',
                'description' => 'Department of Electrical Power Engineering focusing on power systems and energy distribution.',
                'established' => 2000,
                'total_staff' => Staff::where('department', 'Electrical Power Engineering')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Electrical Power Engineering')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Electrical Power Engineering')
                    ->where('status', 'approved')
                    ->count()
            ],
            'Electronics Engineering' => [
                'name' => 'Electronics Engineering',
                'code' => 'EE',
                'head' => 'Dr. Sarah Wilson',
                'email' => 'hod.electronics@wytu.edu.mm',
                'phone' => '+95 1 234570',
                'location' => 'Electronics Building, Room 401',
                'description' => 'Department of Electronics Engineering specializing in circuit design and embedded systems.',
                'established' => 2001,
                'total_staff' => Staff::where('department', 'Electronics Engineering')->count(),
                'active_students' => Application::where('department', 'Electronics Engineering')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Mechanical Engineering' => [
                'name' => 'Mechanical Engineering',
                'code' => 'ME',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာမိုက်ကယ်စူ',
                'email' => 'hod.mechanical@admin.com',
=======
                'head' => 'Dr. Michael Johnson',
                'email' => 'hod.mechanical@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234571',
                'location' => 'Mechanical Building, Room 501',
                'description' => 'Department of Mechanical Engineering focusing on machine design and thermal systems.',
                'established' => 1999,
                'total_staff' => Staff::where('department', 'Mechanical Engineering')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Mechanical Engineering')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Mechanical Engineering')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Chemical Engineering' => [
                'name' => 'Chemical Engineering',
                'code' => 'CHE',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာအဲအဲ',
                'email' => 'hod.chemical@admin.com',
=======
                'head' => 'Dr. Emily Davis',
                'email' => 'hod.chemical@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234572',
                'location' => 'Chemical Building, Room 601',
                'description' => 'Department of Chemical Engineering specializing in process engineering and materials science.',
                'established' => 2002,
                'total_staff' => Staff::where('department', 'Chemical Engineering')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Chemical Engineering')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Chemical Engineering')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Architecture' => [
                'name' => 'Architecture',
                'code' => 'ARCH',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာစမစ်',
                'email' => 'hod.architecture@admin.com',
=======
                'head' => 'Dr. David Miller',
                'email' => 'hod.architecture@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234573',
                'location' => 'Architecture Building, Room 701',
                'description' => 'Department of Architecture focusing on architectural design and urban planning.',
                'established' => 2003,
                'total_staff' => Staff::where('department', 'Architecture')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Architecture')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Architecture')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Biotechnology' => [
                'name' => 'Biotechnology',
                'code' => 'BIO',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာမမ',
                'email' => 'hod.biotech@admin.com',
=======
                'head' => 'Dr. Lisa Anderson',
                'email' => 'hod.biotech@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234574',
                'location' => 'Biotech Building, Room 801',
                'description' => 'Department of Biotechnology specializing in genetic engineering and bioprocess technology.',
                'established' => 2004,
                'total_staff' => Staff::where('department', 'Biotechnology')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Biotechnology')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Biotechnology')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Textile Engineering' => [
                'name' => 'Textile Engineering',
                'code' => 'TEX',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာကိုကို',
                'email' => 'hod.textile@admin.com',
=======
                'head' => 'Dr. James Wilson',
                'email' => 'hod.textile@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234575',
                'location' => 'Textile Building, Room 901',
                'description' => 'Department of Textile Engineering focusing on textile manufacturing and fashion technology.',
                'established' => 2005,
                'total_staff' => Staff::where('department', 'Textile Engineering')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Textile Engineering')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Textile Engineering')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Automobile Engineering' => [
                'name' => 'Automobile Engineering',
                'code' => 'AE',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာနေရောင်အလင်း',
                'email' => 'hod.automobile@admin.com',
=======
                'head' => 'Dr. William Taylor',
                'email' => 'hod.automobile@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234576',
                'location' => 'Automobile Building, Room 1001',
                'description' => 'Department of Automobile Engineering focusing on vehicle design and automotive systems.',
                'established' => 2006,
                'total_staff' => Staff::where('department', 'Automobile Engineering')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Automobile Engineering')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Automobile Engineering')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Mechatronic Engineering' => [
                'name' => 'Mechatronic Engineering',
                'code' => 'MCE',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာလရောင်',
                'email' => 'hod.mechatronic@admin.com',
=======
                'head' => 'Dr. Patricia Harris',
                'email' => 'hod.mechatronic@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234577',
                'location' => 'Mechatronic Building, Room 1101',
                'description' => 'Department of Mechatronic Engineering specializing in robotics and automation systems.',
                'established' => 2007,
                'total_staff' => Staff::where('department', 'Mechatronic Engineering')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Mechatronic Engineering')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Mechatronic Engineering')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ],
            'Metallurgy Engineering' => [
                'name' => 'Metallurgy Engineering',
                'code' => 'MET',
<<<<<<< HEAD
                'head' => 'ဒေါက်တာသန်းထွန်း',
                'email' => 'hod.metallurgy@admin.com',
=======
                'head' => 'Dr. Richard Clark',
                'email' => 'hod.metallurgy@wytu.edu.mm',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'phone' => '+95 1 234578',
                'location' => 'Metallurgy Building, Room 1201',
                'description' => 'Department of Metallurgy Engineering focusing on materials science and metal processing.',
                'established' => 2008,
                'total_staff' => Staff::where('department', 'Metallurgy Engineering')->count(),
<<<<<<< HEAD
                'active_students' => Student::where('department', 'Metallurgy Engineering')
                    ->where('status', 'active')
=======
                'active_students' => Application::where('department', 'Metallurgy Engineering')
                    ->where('status', 'approved')
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    ->count()
            ]
        ];

        return $departments[$department] ?? [
            'name' => $department,
            'code' => 'DEPT',
            'head' => 'Department Head',
            'email' => 'hod@wytu.edu.mm',
            'phone' => '+95 1 234567',
            'location' => 'Main Campus',
            'description' => 'Department description not available.',
            'established' => 2000,
            'total_staff' => 0,
            'active_students' => 0
        ];
    }

    private function getRecentActivities($department)
    {
<<<<<<< HEAD
        return Application::where('assigned_department', $department)
=======
        return Application::where('department', $department)
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function getMonthlyStats($department)
    {
        return [
<<<<<<< HEAD
            'total_applications_month' => Application::where('assigned_department', $department)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'approved_this_month' => Application::where('assigned_department', $department)
=======
            'total_applications_month' => Application::where('department', $department)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'approved_this_month' => Application::where('department', $department)
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                ->where('status', 'approved')
                ->whereMonth('final_approved_at', now()->month)
                ->whereYear('final_approved_at', now()->year)
                ->count(),
<<<<<<< HEAD
            'pending_academic' => Application::where('assigned_department', $department)
                ->where('status', 'payment_verified')
                ->count(),
            'rejected_applications' => Application::where('assigned_department', $department)
=======
            'pending_academic' => Application::where('department', $department)
                ->where('status', 'payment_verified')
                ->count(),
            'rejected_applications' => Application::where('department', $department)
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                ->where('status', 'rejected')
                ->count(),
        ];
    }

    public function finalApprove($id)
    {
        $admin = Auth::guard('admin')->user();
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }

        $department = $this->getHodDepartment($admin);
        $application = Application::findOrFail($id);
        
        // Check if application belongs to HOD's department
        if ($application->assigned_department !== $department) {
=======
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $application = Application::findOrFail($id);
        
        // Check if application belongs to HOD's department
        if ($application->department !== $admin->department) {
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            abort(403, 'Access denied. You can only approve applications from your department.');
        }

        if ($application->status !== 'academic_approved') {
            return redirect()->back()->with('error', 'Application must be academically approved first.');
        }

        try {
            // Update application with final approval
            $application->update([
                'status' => 'approved',
                'final_approved_by' => $admin->id,
                'final_approved_at' => now(),
            ]);

<<<<<<< HEAD
            // Update student status to active (if student exists)
            $student = Student::where('application_id', $application->id)->first();
            if ($student) {
                $student->update([
                    'status' => 'active',
=======
            // Update student status to active
            $student = Student::where('student_id', $application->student_id)->first();
            if ($student) {
                $student->update([
                    'status' => 'active',
                    'activated_at' => now(),
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                ]);
            }

            return redirect()->back()->with('success', 'Application finally approved! Student account is now active.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Final approval failed: ' . $e->getMessage());
        }
    }

    public function approveFinal($id)
    {
        return $this->finalApprove($id);
    }

    public function hodReject(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
=======
        if ($admin->role !== 'hod_admin') {
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            abort(403, 'Access denied. HOD only.');
        }

        $request->validate([
            'notes' => 'required|string|max:500'
        ]);

<<<<<<< HEAD
        $department = $this->getHodDepartment($admin);
        $application = Application::findOrFail($id);
        
        if ($application->assigned_department !== $department) {
=======
        $application = Application::findOrFail($id);
        
        if ($application->department !== $admin->department) {
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            abort(403, 'Access denied. You can only reject applications from your department.');
        }

        if ($application->status !== 'academic_approved') {
            return redirect()->back()->with('error', 'Application is not in correct status for rejection.');
        }

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->notes,
            'rejected_by' => $admin->id,
            'rejected_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Application rejected successfully.');
    }

    public function myDepartment()
    {
        $admin = Auth::guard('admin')->user();
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }

        $department = $this->getHodDepartment($admin);
        $departmentStats = [
            'total_students' => Student::where('department', $department)
                ->where('status', 'active')
                ->count(),
            'pending_applications' => Application::where('assigned_department', $department)
                ->where('status', 'academic_approved')
                ->count(),
            'total_applications' => Application::where('assigned_department', $department)->count(),
        ];

        $departmentInfo = $this->getDepartmentInfo($department);
=======
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $departmentStats = [
            'total_students' => Application::where('department', $admin->department)
                ->where('status', 'approved')
                ->count(),
            'pending_applications' => Application::where('department', $admin->department)
                ->where('status', 'academic_approved')
                ->count(),
            'total_applications' => Application::where('department', $admin->department)->count(),
        ];

        $departmentInfo = $this->getDepartmentInfo($admin->department);
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c

        return view('admin.hod.department', compact('departmentStats', 'admin', 'departmentInfo'));
    }

    public function departmentApplications()
    {
        $admin = Auth::guard('admin')->user();
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }

        $department = $this->getHodDepartment($admin);
        $applications = Application::where('assigned_department', $department)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $departmentInfo = $this->getDepartmentInfo($department);
=======
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $applications = Application::where('department', $admin->department)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $departmentInfo = $this->getDepartmentInfo($admin->department);
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c

        return view('admin.hod.department-applications', compact('applications', 'admin', 'departmentInfo'));
    }

<<<<<<< HEAD
    /**
     * View students in HOD's department
     */
    public function departmentStudents()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }

        $department = $this->getHodDepartment($admin);
        $students = Student::where('department', $department)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $departmentInfo = $this->getDepartmentInfo($department);

        return view('admin.hod.department-students', compact('students', 'admin', 'departmentInfo'));
    }

=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
    // Staff Management Methods
    public function staffIndex()
    {
        $admin = Auth::guard('admin')->user();
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }

        $department = $this->getHodDepartment($admin);
        $staff = Staff::where('department', $department)->get();
        $departmentInfo = $this->getDepartmentInfo($department);
=======
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $staff = Staff::where('department', $admin->department)->get();
        $departmentInfo = $this->getDepartmentInfo($admin->department);
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        
        return view('admin.hod.staff', compact('staff', 'admin', 'departmentInfo'));
    }

    public function staffStore(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }

        $department = $this->getHodDepartment($admin);
=======
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'position' => 'required|string|max:255',
        ]);

        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
<<<<<<< HEAD
            'department' => $department,
=======
            'department' => $admin->department,
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        ]);

        return redirect()->back()->with('success', 'Staff member added successfully');
    }

    public function staffUpdate(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }

        $department = $this->getHodDepartment($admin);
        $staff = Staff::findOrFail($id);
        
        if ($staff->department !== $department) {
=======
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $staff = Staff::findOrFail($id);
        
        if ($staff->department !== $admin->department) {
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
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
        
<<<<<<< HEAD
        if (!$this->isHodAdmin($admin)) {
            abort(403, 'Access denied. HOD only.');
        }

        $department = $this->getHodDepartment($admin);
        $staff = Staff::findOrFail($id);
        
        if ($staff->department !== $department) {
=======
        if ($admin->role !== 'hod_admin') {
            abort(403, 'Access denied. HOD only.');
        }

        $staff = Staff::findOrFail($id);
        
        if ($staff->department !== $admin->department) {
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            abort(403, 'Access denied.');
        }

        $staff->delete();

        return redirect()->back()->with('success', 'Staff member deleted successfully');
    }
}
