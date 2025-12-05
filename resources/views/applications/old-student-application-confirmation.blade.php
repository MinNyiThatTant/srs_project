<!DOCTYPE html>
<html>
<head>
    <title>Application Submitted - WYTU University</title>
</head>
<body>
    <h2>Application Submitted Successfully</h2>
    <p>Dear {{ $student->name }},</p>
    
    <p>Your application for the next academic year has been submitted successfully.</p>
    
    <h3>Application Details:</h3>
    <ul>
        <li><strong>Application ID:</strong> {{ $application_id }}</li>
        <li><strong>Academic Year:</strong> {{ $academic_year }}</li>
        <li><strong>Application Fee:</strong> {{ $fee }}</li>
    </ul>
    
    <p><strong>Next Steps:</strong></p>
    <ol>
        <li>Complete your payment: <a href="{{ $payment_url }}">{{ $payment_url }}</a></li>
        <li>Your application will be reviewed by the academic department</li>
        <li>You will receive notification once your application is approved</li>
    </ol>
    
    <p>Thank you for choosing West Yangon Technological University.</p>
    
    <p>Best regards,<br>
    WYTU University Administration</p>
</body>
</html>