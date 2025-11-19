@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <h1>Teacher Administrator Dashboard</h1>
    <p>Welcome, {{ Auth::guard('admin')->user()->name }} (Teacher Admin)</p>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Teachers</h5>
                    <h3>75</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Active Classes</h5>
                    <h3>45</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Teaching Assignments</h5>
                    <h3>120</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Teacher Requests</h5>
                    <h3>18</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection