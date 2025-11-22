@extends('layouts.master')

@section('title', 'Student Dashboard')
@section('body-class', 'light-blue-bg')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Welcome Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Welcome, {{ session('student.name') }}
                    </h4>
                    <div>
                        <span class="badge bg-light text-dark">{{ session('student.student_id') }}</span>
                        <a href="{{ route('student.logout') }}" class="btn btn-sm btn-light ms-2">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Student Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <th>Student ID:</th>
                                            <td>{{ session('student.student_id') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{ session('student.name') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ session('student.email') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <th>Department:</th>
                                            <td>{{ session('student.department') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Application ID:</th>
                                            <td>{{ session('student.application_id') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td><span class="badge bg-success">Active Student</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-light rounded p-3">
                                <i class="bi bi-person-check display-4 text-primary mb-3"></i>
                                <h5>Verified Student</h5>
                                <p class="text-muted mb-0">Account Active</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-journal-text display-6 mb-2"></i>
                            <h4>Application</h4>
                            <p class="mb-0">Approved</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle display-6 mb-2"></i>
                            <h4>Payment</h4>
                            <p class="mb-0">Verified</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-building display-6 mb-2"></i>
                            <h4>Department</h4>
                            <p class="mb-0">{{ session('student.department') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-check display-6 mb-2"></i>
                            <h4>Academic Year</h4>
                            <p class="mb-0">{{ date('Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('student.profile') }}" class="btn btn-outline-primary text-start">
                                    <i class="bi bi-person me-2"></i> View My Profile
                                </a>
                                <a href="{{ route('student.application.status') }}" class="btn btn-outline-success text-start">
                                    <i class="bi bi-clipboard-check me-2"></i> Application Status
                                </a>
                                <button class="btn btn-outline-info text-start">
                                    <i class="bi bi-book me-2"></i> Course Registration
                                </button>
                                <button class="btn btn-outline-warning text-start">
                                    <i class="bi bi-credit-card me-2"></i> Fee Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-bell me-2"></i>Recent Updates</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Application Approved</h6>
                                        <small>3 days ago</small>
                                    </div>
                                    <p class="mb-1">Your application has been finally approved.</p>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Welcome to WYTU</h6>
                                        <small>1 week ago</small>
                                    </div>
                                    <p class="mb-1">Welcome to West Yangon Technological University.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection