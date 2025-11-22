<!-- resources/views/emails/student-credentials.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Account Credentials</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 20px; }
        .credentials { background: white; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <h2>Student Account Credentials</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $student->name }},</p>
            
            <p>Congratulations! Your student application has been approved. Below are your login credentials:</p>
            
            <div class="credentials">
                <h3>Your Login Details:</h3>
                <p><strong>Student ID:</strong> {{ $student->student_number }}</p>
                <p><strong>Password:</strong> {{ $tempPassword }}</p>
                <p><strong>Login URL:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
            </div>
            
            <p><strong>Important Instructions:</strong></p>
            <ul>
                <li>Use the Student ID and Password above to login</li>
                <li>Change your password after first login for security</li>
                <li>Keep your credentials confidential</li>
                <li>If you face any issues, contact the administration</li>
            </ul>
            
            <p>Best regards,<br>
            {{ config('app.name') }} Administration</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>