<?php
// app/Http/Controllers/StudentRegistrationController.php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentRegistrationController extends Controller
{
    /**
     * Show student registration form
     */
    public function showRegistrationForm()
    {
        return view('admin.students.new-student');
    }

    /**
     * Process student registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'program' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('student.register')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Generate unique application ID
            $applicationId = 'APP' . date('YmdHis') . Str::upper(Str::random(4));

            // Create student record
            $student = Student::create([
                'application_id' => $applicationId,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'program' => $request->program,
                'status' => 'pending',
            ]);

            // TODO: Send notification to finance admin
            // $this->notifyFinanceAdmin($student);

            return redirect()->route('student.registration.success')
                ->with('application_id', $applicationId)
                ->with('success', 'Your registration has been submitted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('student.register')
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show registration success page
     */
    public function registrationSuccess()
    {
        if (!session('application_id')) {
            return redirect()->route('student.register');
        }

        return view('student.registration-success');
    }

    /**
     * Notify finance admin about new application
     */
    private function notifyFinanceAdmin($student)
    {
        // Implement email notification to finance admin
        // You can use Laravel notifications or email
    }
}