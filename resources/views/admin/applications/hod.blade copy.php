@extends('layouts.admin')

@section('title', 'HOD Applications - WYTU')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie me-2"></i>Department Applications - {{ Auth::guard('admin')->user()->department }}
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>App ID</th>
                                    <th>Name</th>
                                    <th>Academic Approved At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                <tr>
                                    <td>WYTU-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $application->name }}</td>
                                    <td>{{ $application->academic_approved_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $application->status_badge }}">
                                            {{ $application->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#finalApproveModal{{ $application->id }}">
                                            <i class="fas fa-check-double"></i> Final Approve
                                        </button>
                                    </td>
                                </tr>

                                <!-- Final Approve Modal -->
                                <div class="modal fade" id="finalApproveModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Final Approval</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.applications.final-approve', $application->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Grant final approval to <strong>{{ $application->name }}</strong>?</p>
                                                    <p>This will generate a student ID and complete the admission process.</p>
                                                    <div class="mb-3">
                                                        <label for="notes" class="form-label">Notes (Optional)</label>
                                                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                                  placeholder="Add any final notes..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Final Approve</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($applications->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h4>No applications pending final approval</h4>
                        <p class="text-muted">All department applications have been processed.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection