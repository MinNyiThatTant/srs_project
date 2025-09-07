@extends('admin.master')

@section('content')
<div class="container-fluid">
    <h1>Finance Administrator Dashboard</h1>
    <p>Welcome, {{ Auth::guard('admin')->user()->name }} (Finance Admin)</p>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Budget</h5>
                    <h3>$2.5M</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Expenses</h5>
                    <h3>$1.8M</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Pending Invoices</h5>
                    <h3>32</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Financial Reports</h5>
                    <h3>15</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection