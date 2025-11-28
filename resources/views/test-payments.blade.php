<!DOCTYPE html>
<html>
<head>
    <title>Test Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Payment System</h1>
        <p class="text-muted">For development testing only</p>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5>Pending Applications for Testing</h5>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>App ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                <tr>
                                    <td>{{ $application->application_id }}</td>
                                    <td>{{ $application->name }}</td>
                                    <td>{{ $application->email }}</td>
                                    <td>{{ $application->department }}</td>
                                    <td>
                                        <span class="badge bg-{{ $application->application_type === 'new' ? 'primary' : 'info' }}">
                                            {{ $application->application_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ url('/test-payment-success/' . $application->id) }}" 
                                           class="btn btn-success btn-sm"
                                           onclick="return confirm('Simulate successful payment for {{ $application->name }}?')">
                                            Simulate Payment Success
                                        </a>
                                        <a href="{{ route('payment.show', $application->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            Real Payment Page
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        No pending applications found for testing.
                        <a href="{{ url('/new-student-apply') }}" class="alert-link">Create a test application</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>