<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Academic Approval - WYTU</title>
</head>
<body>
    <h2>Application Status Update</h2>
    <p>Dear {{ $application->name }},</p>
    
    <p>Your application (ID: {{ $application->application_id }}) has been academically approved.</p>
    
    <p><strong>Next Steps:</strong></p>
    <ul>
        <li>Your application will now proceed to final approval</li>
        <li>You will receive your student credentials after final approval</li>
        <li>Check your application status regularly</li>
    </ul>
    
    <p>Best regards,<br>
    WYTU Academic Affairs</p>
</body>
</html>