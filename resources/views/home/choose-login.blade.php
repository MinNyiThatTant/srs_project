@extends('master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Choose Login Type</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Student Login</h5>
                                    <p class="card-text">Access your student dashboard</p>
                                    <a href="{{ route('login') }}" class="btn btn-primary">Student Login</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Admin Login</h5>
                                    <p class="card-text">Access admin management panel</p>
                                    <a href="{{ route('admin.login') }}" class="btn btn-danger">Admin Login</a>
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