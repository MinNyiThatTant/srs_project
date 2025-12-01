<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Account Credentials - WYTU University</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8fafc;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e2e8f0;
        }
        .credentials {
            background: white;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background: #f1f5f9;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #64748b;
            font-size: 14px;
        }
        .warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>WYTU University</h1>
        <h2>Welcome to Our Academic Community!</h2>
    </div>
    
    <div class="content">
        <h3>Dear {{ $student->name }},</h3>
        
        <p>Congratulations! Your application has been approved and you have been assigned to the <strong>{{ $department }}</strong> department.</p>
        
        <p>Your student account has been created with the following credentials:</p>
        
        <div class="credentials">
            <div class="credential-item">
                <strong>Student ID:</strong> {{ $student->student_id }}
            </div>
            <div class="credential-item">
                <strong>Email:</strong> {{ $student->email }}
            </div>
            <div class="credential-item">
                <strong>Password:</strong> {{ $password }}
            </div>
            <div class="credential-item">
                <strong>Department:</strong> {{ $department }}
            </div>
        </div>

        <div class="warning">
            <strong>Important Security Notice:</strong> 
            Please change your password immediately after your first login for security reasons.
        </div>

        <p>You can now access your student dashboard using the button below:</p>
        
        <a href="{{ $loginUrl }}" class="button">Access Student Dashboard</a>
        
        <p>If the button doesn't work, copy and paste this link in your browser:<br>
        <small>{{ $loginUrl }}</small></p>

        <div class="footer">
            <p>Best regards,<br>
            <strong>Academic Affairs Office</strong><br>
            WYTU University<br>
            Phone: +95 1 234 5678<br>
            Email: academic@wytu.edu.mm</p>
            
            <p style="margin-top: 20px; font-size: 12px; color: #94a3b8;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>