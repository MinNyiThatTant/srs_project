@extends('admin.master')

@section('content')
<div class="container-fluid">
    <h1>HOD Admin Dashboard</h1>
    <p>Welcome, {{ Auth::guard('admin')->user()->name }} (HOD Admin)</p>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <h3>{{ $totalUsers ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Departments</h5>
                    <h3>{{ $totalDepartments ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>HOD Admins</h5>
                    <h3>{{ $totalHods ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Students</h5>
                    <h3>{{ $totalStudents ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('admin.global') }}" class="btn btn-primary">Global Admin Panel</a>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Manage Users</a>
    </div>
</div>
@endsection