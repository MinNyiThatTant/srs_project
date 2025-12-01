<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Approved - WYTU</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #059669; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 20px; border-radius: 0 0 8px 8px; }
        .info-box { background: white; padding: 15px; border: 2px solid #059669; border-radius: 5px; margin: 15px 0; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #6b7280; }
        .next-steps { background: #d1fae5; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>West Yangon Technological University</h1>
            <h2>Application Approved</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $application->name }},</p>
            
            <p>We are pleased to inform you that your application has been <strong>approved</strong> by the {{ $approvalType }}.</p>
            
            <div class="info-box">
                <h3>Application Details:</h3>
                <p><strong>Application ID:</strong> {{ $application->application_id }}</p>
                <p><strong>Name:</strong> {{ $application->name }}</p>
                <p><strong>Department:</strong> {{ $application->department }}</p>
                <p><strong>Application Type:</strong> {{ ucfirst($application->application_type) }} Student</p>
                <p><strong>Approval Date:</strong> {{ $approvalDate }}</p>
                <p><strong>Approved By:</strong> {{ $approvedBy }}</p>
            </div>

            @if($nextSteps)
            <div class="next-steps">
                <h3>Next Steps:</h3>
                {!! $nextSteps !!}
            </div>
            @endif

            <p><strong>Application Status:</strong> 
                <span style="color: #059669; font-weight: bold;">{{ ucfirst(str_replace('_', ' ', $application->status)) }}</span>
            </p>

            <p>You can check your application status at any time by visiting: 
                <a href="{{ $statusUrl }}">{{ $statusUrl }}</a>
            </p>

            <p>If you have any questions, please contact the administration office.</p>
            
            <p>Best regards,<br>
            WYTU Administration<br>
            West Yangon Technological University</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} West Yangon Technological University. All rights reserved.</p>
        </div>
    </div>
</body>
</html>