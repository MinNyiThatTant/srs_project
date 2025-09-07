@extends('admin.master')

@section('content')
<div class="container-fluid">
    <h1>Head of Staff Affairs Dashboard</h1>
    <p>Welcome, {{ Auth::guard('admin')->user()->name }} (HSA Admin)</p>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Staff</h5>
                    <h3>120</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>New Hires</h5>
                    <h3>8</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Training Programs</h5>
                    <h3>15</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Staff Requests</h5>
                    <h3>23</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection