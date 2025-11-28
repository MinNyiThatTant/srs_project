<!DOCTYPE html>
<html>
<head>
    <title>Your Student Account Credentials - WYTU University</title>
</head>
<body>
    <h2>Welcome to WYTU University!</h2>
    
    <p>Dear {{ $student->name }},</p>
    
    <p>Your student account has been created successfully. Here are your login credentials:</p>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
        <p><strong>Password:</strong> {{ $password }}</p>
        <p><strong>Department:</strong> {{ $student->department }}</p>
    </div>

    @if(isset($status) && $status === 'pending')
    <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <p><strong>Note:</strong> Your account is currently <strong>pending final approval</strong> from the Department Head. 
        You will be able to access all student features once your account is fully approved.</p>
    </div>
    @else
    <div style="background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <p><strong>Note:</strong> Your account has been fully approved and is now active.</p>
    </div>
    @endif

    <p>You can login to the student portal using the following link:</p>
    <p><a href="{{ $loginUrl }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Login to Student Portal</a></p>
    
    <p><strong>Important Security Notes:</strong></p>
    <ul>
        <li>Keep your student ID and password confidential</li>
        <li>Change your password after first login</li>
        <li>Do not share your login credentials with anyone</li>
    </ul>
    
    <p>If you have any questions, please contact the administration office.</p>
    
    <p>Best regards,<br>
    WYTU University Administration</p>
</body>
</html>