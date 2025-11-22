<?php
// app/Http/Controllers/HaaAdminController.php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class HaaAdminController extends Controller
{
    public function dashboard()
    {
        $financeApprovedStudents = Student::where('status', 'finance_approved')->get();
        $activeStudents = Student::where('status', 'active')->get();

        return view('admin.haa.dashboard', compact('financeApprovedStudents', 'activeStudents'));
    }

    public function approveStudent($id)
    {
        $student = Student::findOrFail($id);
        
        // Generate student number and temporary password
        $studentNumber = $student->generateStudentNumber();
        $tempPassword = Str::random(8);

        $student->update([
            'student_number' => $studentNumber,
            'password' => Hash::make($tempPassword),
            'status' => 'active',
            'haa_approved_at' => now()
        ]);

        // Send email with credentials using Google SMTP
        $this->sendStudentCredentials($student, $tempPassword);

        return redirect()->back()->with('success', 'Student approved and credentials sent via email');
    }

    public function rejectStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update([
            'status' => 'rejected',
            'haa_notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Student application rejected');
    }

    /**
     * Send student credentials via email using Google SMTP
     */
    private function sendStudentCredentials($student, $tempPassword)
    {
        $data = [
            'student' => $student,
            'tempPassword' => $tempPassword,
            'loginUrl' => route('student.login')
        ];

        try {
            Mail::send('emails.student-credentials', $data, function($message) use ($student) {
                $message->to($student->email)
                        ->subject('Your Student Account Credentials - ' . config('app.name'))
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
            
            \Log::info("Credentials email sent to: " . $student->email);
            
        } catch (\Exception $e) {
            \Log::error("Failed to send email to: " . $student->email . " Error: " . $e->getMessage());
        }
    }
}