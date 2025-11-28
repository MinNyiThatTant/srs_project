@extends('admin.layouts.master')

@section('title', 'Academic Applications')
@section('page-title', 'Applications for Academic Review')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Applications Pending Academic Approval</h4>
    </div>
    <div class="card-body">
        @if($applications->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>App ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr>
                        <td>{{ $application->application_id }}</td>
                        <td>{{ $application->name }}</td>
                        <td>{{ $application->department }}</td>
                        <td>{{ $application->email }}</td>
                        <td>
                            <span class="badge bg-success">Verified</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.academic.application.view', $application->id) }}" 
                                   class="btn btn-sm btn-primary">View</a>
                                <form action="{{ route('admin.academic.approve-application', $application->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" 
                                            onclick="return confirm('Approve this application?')">Approve</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            No applications pending academic review.
        </div>
        @endif
    </div>
</div>
@endsection