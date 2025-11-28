<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // ADD THIS IMPORT

class HaaController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }
        
        $stats = $this->getDashboardStats();
        return view('admin.dashboard-academic', compact('stats', 'admin'));
    }

    private function getDashboardStats()
    {
        $stats = [
            'pending_reviews' => Application::where('status', 'payment_verified')
                ->where('payment_status', 'verified')
                ->count(),
            'approved_today' => Application::where('status', 'academic_approved')
                ->whereDate('academic_approved_at', today())
                ->count(),
            'total_reviewed' => Application::whereIn('status', ['academic_approved', 'academic_rejected'])
                ->count(),
            'total_students' => Application::where('status', 'academic_approved')->count(),
            'recent_applications' => Application::where('status', 'payment_verified')
                ->where('payment_status', 'verified')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];

        return $stats;
    }

    public function academicApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $applications = Application::where('status', 'payment_verified')
            ->where('payment_status', 'verified')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.applications.academic', compact('applications', 'admin'));
    }

    public function academicApprove($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'academic_approved',
            'academic_approved_by' => $admin->id,
            'academic_approved_at' => now(),
        ]);

        // Send approval email
        $this->sendAcademicApprovalEmail($application);

        return redirect()->back()->with('success', 'Application academically approved');
    }

    public function academicReject(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'academic_rejected',
            'rejection_notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Application academically rejected');
    }

    public function academicAffairs()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        return view('admin.academic.affairs', compact('admin'));
    }

    public function courseManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        return view('admin.courses.management', compact('admin'));
    }

    public function approveAcademic($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'academic_approved',
            'academic_approved_by' => $admin->id,
            'academic_approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Application academically approved');
    }

    public function viewApplication($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);
        return view('admin.applications.view', compact('application', 'admin'));
    }

    /**
     * Send academic approval email using Gmail
     */
    private function sendAcademicApprovalEmail($application)
    {
        try {
            Log::info('Sending academic approval email', [
                'application_id' => $application->id,
                'email' => $application->email
            ]);

            Mail::send('emails.academic-approved', [
                'application' => $application
            ], function ($message) use ($application) {
                $message->to($application->email)
                        ->subject('Application Academically Approved - WYTU');
            });

            Log::info('Academic approval email sent successfully');

        } catch (\Exception $e) {
            Log::error('Academic approval email failed: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'error_details' => $e->getTraceAsString()
            ]);
        }
    }
}