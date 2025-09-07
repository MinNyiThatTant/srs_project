@extends('admin.master')

@section('content')
<div class="container-fluid">
    <h1>Head of Academic Affairs Dashboard</h1>
    <p>Welcome, {{ Auth::guard('admin')->user()->name }} (HAA Admin)</p>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Academic Programs</h5>
                    <h3>25</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Courses</h5>
                    <h3>150</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Faculty Members</h5>
                    <h3>85</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Academic Events</h5>
                    <h3>12</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection