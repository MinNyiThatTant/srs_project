@extends('layouts.admin')

@section('title', 'Finance Applications - WYTU')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="fas fa-money-bill-wave me-2"></i>Finance Applications
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
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                <tr>
                                    <td>WYTU-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $application->name }}</td>
                                    <td>{{ $application->department }}</td>
                                    <td>{{ $application->application_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge {{ $application->status_badge }}">
                                            {{ $application->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($application->payment_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($application->payment_status === 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($application->status === \App\Models\Application::STATUS_PAYMENT_PENDING)
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#verifyModal{{ $application->id }}">
                                            <i class="fas fa-check"></i> Verify Payment
                                        </button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Verify Payment Modal -->
                                <div class="modal fade" id="verifyModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Verify Payment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.applications.verify-payment', $application->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Are you sure you want to verify payment for <strong>{{ $application->name }}</strong>?</p>
                                                    <div class="mb-3">
                                                        <label for="notes" class="form-label">Notes (Optional)</label>
                                                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                                  placeholder="Add any notes about payment verification..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Verify Payment</button>
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
                        <h4>No applications pending payment verification</h4>
                        <p class="text-muted">All payment applications have been processed.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection