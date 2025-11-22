@extends('layouts.master')

@section('title', 'Setup Password - WYTU')

@section('body-class', 'light-blue-bg')

@section('content')
<section class="main pt-5"
    style="background-image: url({{ asset('images/hero-bg.png') }}); height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div class="container my-4 d-flex justify-content-center align-items-center" style="height: 100%;">
        <div class="card mx-auto" style="width: 25rem;">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Setup Your Password</h4>
            </div>
            
            <div class="card-body">
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif

                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif

                <div class="alert alert-info">
                    <strong>Welcome, {{ $application->name }}!</strong><br>
                    Student ID: <strong>{{ $application->student_id }}</strong><br>
                    Please set your password to access the student portal.
                </div>

                <form action="{{ route('setup.password.process', $application->application_id) }}" method="post">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Enter new password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" 
                            placeholder="Confirm new password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-key"></i> Set Password
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection