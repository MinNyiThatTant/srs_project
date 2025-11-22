@extends('admin.layouts.master')

@section('title', 'HOD Applications')
@section('page-title', 'Applications for Final Approval')
@section('page-subtitle', 'Review and grant final approval')
@section('breadcrumb', 'Applications')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Applications from {{ $admin->department }}</h4>
        <p class="text-muted mb-0">Applications that have been academically approved and are ready for final approval</p>
    </div>
    <div class="card-body">
        @if($applications->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>App ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Matriculation Score</th>
                        <th>Academic Status</th>
                        <th>Applied Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr>
                        <td>{{ $application->application_id ?? 'N/A' }}</td>
                        <td>{{ $application->name }}</td>
                        <td>{{ $application->email }}</td>
                        <td>{{ $application->phone }}</td>
                        <td>{{ $application->matriculation_score ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-success">Academic Approved</span>
                        </td>
                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.applications.view', $application->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <form action="{{ route('admin.approve.final', $application->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                            onclick="return confirm('Grant final approval to this application?')">
                                        <i class="bi bi-check"></i> Final Approve
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $applications->links() }}
        </div>
        @else
        <div class="alert alert-info">
            <p class="mb-0">No applications found for final approval in your department.</p>
        </div>
        @endif
    </div>
</div>
@endsection