@extends('layouts.admin')

@section('title', 'Academic Applications - WYTU')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">
                        <i class="fas fa-graduation-cap me-2"></i>Academic Applications
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
                                    <th>Department</th>
                                    <th>Matriculation Score</th>
                                    <th>Payment Verified At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                <tr>
                                    <td>WYTU-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $application->name }}</td>
                                    <td>{{ $application->department }}</td>
                                    <td>{{ $application->matriculation_score }}/600</td>
                                    <td>{{ $application->payment_verified_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
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
                                                data-bs-target="#approveModal{{ $application->id }}">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $application->id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Academic Approval</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.applications.academic-approve', $application->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Approve <strong>{{ $application->name }}</strong> for academic admission?</p>
                                                    <div class="mb-3">
                                                        <label for="notes" class="form-label">Notes (Optional)</label>
                                                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                                  placeholder="Add any notes about academic approval..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Academic Approve</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Application</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.applications.academic-reject', $application->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Reject application for <strong>{{ $application->name }}</strong>?</p>
                                                    <div class="mb-3">
                                                        <label for="rejection_reason" class="form-label">Rejection Reason</label>
                                                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" 
                                                                  placeholder="Please provide reason for rejection..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject Application</button>
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
                        <h4>No applications pending academic approval</h4>
                        <p class="text-muted">All academic applications have been processed.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection