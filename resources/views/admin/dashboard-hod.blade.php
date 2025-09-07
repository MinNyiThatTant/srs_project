@extends('admin.master')

@section('content')
<div class="container-fluid">
    <h1>HOD Admin Dashboard</h1>
    <p>Welcome, {{ Auth::guard('admin')->user()->name }} (HOD - {{ $department->name }})</p>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>My Department</h5>
                    <h4>{{ $department->name }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Students</h5>
                    <h3>{{ $department->students->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Department Code</h5>
                    <h3>{{ $department->code }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('admin.hod') }}" class="btn btn-primary">HOD Panel</a>
        <a href="{{ route('admin.my-department') }}" class="btn btn-secondary">My Department</a>
    </div>
</div>
@endsection