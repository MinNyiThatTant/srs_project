<?php

namespace App\Services;

use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentCredentialsMail;

class StudentApprovalService
{
    public function approveStudent(Application $application, $approvedBy = null)
    {
        // Generate student credentials
        $studentId = $application->generateStudentId();
        $temporaryPassword = User::generateTemporaryPassword();

        // Create user account
        $user = User::create([
            'name' => $application->name,
            'email' => $application->email,
            'password' => Hash::make($temporaryPassword),
            'student_id' => $studentId,
            'phone' => $application->phone,
            'date_of_birth' => $application->date_of_birth,
            'gender' => $application->gender,
            'department' => $application->department,
            'academic_year' => date('Y'),
            'status' => 'active',
            'application_id' => $application->id
        ]);

        // Update application
        $application->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
            'user_id' => $user->id
        ]);

        // Send credentials email (you can enable this later)
        // try {
        //     Mail::to($application->email)->send(new StudentCredentialsMail($studentId, $temporaryPassword));
        // } catch (\Exception $e) {
        //     \Log::error('Failed to send student credentials email: ' . $e->getMessage());
        // }

        return [
            'user' => $user,
            'student_id' => $studentId,
            'temporary_password' => $temporaryPassword
        ];
    }

    public function rejectStudent(Application $application, $reason = null, $rejectedBy = null)
    {
        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_by' => $rejectedBy,
            'rejected_at' => now()
        ]);

        return $application;
    }
}