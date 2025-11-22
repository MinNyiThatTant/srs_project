<!-- resources/views/student/registration-success.blade.php -->
@extends('layouts.app')

@section('title', 'Registration Successful')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center">
                    <div class="alert alert-success">
                        <i class="icon-check icon-lg"></i>
                        <h4 class="alert-heading">Registration Submitted Successfully!</h4>
                    </div>
                    
                    <div class="registration-details">
                        <h3>Your Application Details</h3>
                        <div class="application-id-box bg-light p-4 rounded mt-4">
                            <h2 class="text-primary">{{ session('application_id') }}</h2>
                            <p class="text-muted">Keep this Application ID safe for future reference</p>
                        </div>
                        
                        <div class="mt-4">
                            <h5>What happens next?</h5>
                            <ol class="text-left mt-3">
                                <li>Your application will be reviewed by Finance Department</li>
                                <li>After finance approval, HAA will review your application</li>
                                <li>Once fully approved, you'll receive student credentials via email</li>
                                <li>Use the student number and password to login</li>
                            </ol>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary">Go to Homepage</a>
                        <a href="{{ route('student.login') }}" class="btn btn-outline-primary">Student Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection