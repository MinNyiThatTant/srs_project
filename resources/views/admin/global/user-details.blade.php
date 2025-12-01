@extends('admin.layouts.master')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <div class="d-flex">
            <a href="{{ route('admin.global.users') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
            <button class="btn btn-warning mr-2" data-toggle="modal" data-target="#editUserModal">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit User
            </button>
            @if($user->id !== auth()->id())
            <button class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal">
                <i class="fas fa-trash fa-sm text-white-50"></i> Delete User
            </button>
            @endif
        </div>
    </div>

    <!-- User Profile -->
    <div class="row">
        <div class="col-lg-4">
            <!-- User Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Profile</h6>
                </div>
                <div class="card-body text-center">
                    <img class="img-fluid rounded-circle mb-3" 
                         src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                         alt="User Avatar" style="width: 150px; height: 150px; object-fit: cover;">
                    
                    <h4 class="font-weight-bold text-gray-800">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="mb-3">
                        <span class="badge badge-{{ $user->getRoleBadgeColor() }} p-2">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }} p-2">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if($user->phone)
                    <p><i class="fas fa-phone mr-2 text-gray-400"></i> {{ $user->phone }}</p>
                    @endif

                    @if($user->department)
                    <p><i class="fas fa-building mr-2 text-gray-400"></i> {{ $user->department }}</p>
                    @endif

                    <p class="text-muted">
                        <small>Member since {{ $user->created_at->format('M d, Y') }}</small>
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($user->is_active)
                        <button class="btn btn-warning btn-block" id="suspendUserBtn">
                            <i class="fas fa-pause mr-2"></i> Suspend User
                        </button>
                        @else
                        <button class="btn btn-success btn-block" id="activateUserBtn">
                            <i class="fas fa-play mr-2"></i> Activate User
                        </button>
                        @endif
                        
                        <button class="btn btn-info btn-block" data-toggle="modal" data-target="#resetPasswordModal">
                            <i class="fas fa-key mr-2"></i> Reset Password
                        </button>
                        
                        <button class="btn btn-secondary btn-block" data-toggle="modal" data-target="#sendMessageModal">
                            <i class="fas fa-envelope mr-2"></i> Send Message
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- User Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">User ID</th>
                                    <td>
                                        @if($user->role === 'student')
                                        {{ $user->student_id ?? 'N/A' }}
                                        @else
                                        {{ $user->id }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Full Name</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email Address</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">User Role</th>
                                    <td>
                                        <span class="badge badge-{{ $user->getRoleBadgeColor() }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <td>{{ $user->department ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Account Status</th>
                                    <td>
                                        <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Login</th>
                                    <td>
                                        @if($user->last_login_at)
                                        {{ $user->last_login_at->format('M d, Y H:i') }}
                                        @else
                                        <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if($user->address || $user->date_of_birth)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="font-weight-bold text-primary mb-3">Additional Information</h6>
                            <div class="row">
                                @if($user->date_of_birth)
                                <div class="col-md-6">
                                    <p><strong>Date of Birth:</strong> {{ $user->date_of_birth->format('M d, Y') }}</p>
                                </div>
                                @endif
                                @if($user->address)
                                <div class="col-md-6">
                                    <p><strong>Address:</strong> {{ $user->address }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($user->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="font-weight-bold text-primary mb-3">Admin Notes</h6>
                            <div class="alert alert-info">
                                {{ $user->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Activity Log -->
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View Full Log</a>
                </div>
                <div class="card-body">
                    @if($user->activities->count() > 0)
                    <div class="timeline">
                        @foreach($user->activities->take(5) as $activity)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $activity->type_color }}"></div>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold">{{ $activity->description }}</h6>
                                <p class="text-muted mb-1">{{ $activity->created_at->format('M d, Y H:i') }}</p>
                                @if($activity->details)
                                <small class="text-muted">{{ $activity->details }}</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-2x text-gray-300 mb-3"></i>
                        <p class="text-muted">No recent activity found for this user.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- For Student Users: Application Information -->
    @if($user->role === 'student' && $user->application)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Application Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Application ID</th>
                                    <td>
                                        <a href="{{ route('admin.global.applications.view', $user->application->id) }}" class="text-primary">
                                            {{ $user->application->application_id }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <td>{{ $user->application->department }}</td>
                                </tr>
                                <tr>
                                    <th>Application Type</th>
                                    <td>
                                        <span class="badge badge-{{ $user->application->application_type === 'new' ? 'info' : 'warning' }}">
                                            {{ ucfirst($user->application->application_type) }} Student
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Application Status</th>
                                    <td>
                                        <span class="badge {{ $user->application->status_badge }}">
                                            {{ $user->application->status_text }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Applied Date</th>
                                    <td>{{ $user->application->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Status</th>
                                    <td>
                                        <span class="badge {{ $user->application->payment_status_badge }}">
                                            {{ $user->application->payment_status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Matriculation Score</th>
                                    <td>
                                        @if($user->application->matriculation_score)
                                        {{ $user->application->matriculation_score }}/600
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Final Approval</th>
                                    <td>
                                        @if($user->application->final_approved_at)
                                        {{ $user->application->final_approved_at->format('M d, Y') }}
                                        @else
                                        <span class="text-muted">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.global.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit User: {{ $user->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Full Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" value="{{ $user->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_email">Email Address</label>
                                <input type="email" class="form-control" id="edit_email" name="email" value="{{ $user->email }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_phone">Phone Number</label>
                                <input type="text" class="form-control" id="edit_phone" name="phone" value="{{ $user->phone }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_role">User Role</label>
                                <select class="form-control" id="edit_role" name="role" required>
                                    <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="finance_admin" {{ $user->role == 'finance_admin' ? 'selected' : '' }}>Finance Admin</option>
                                    <option value="academic_admin" {{ $user->role == 'academic_admin' ? 'selected' : '' }}>Academic Admin</option>
                                    <option value="hod" {{ $user->role == 'hod' ? 'selected' : '' }}>Head of Department</option>
                                    <option value="global_admin" {{ $user->role == 'global_admin' ? 'selected' : '' }}>Global Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_department">Department</label>
                                <select class="form-control" id="edit_department" name="department">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ $user->department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_status">Account Status</label>
                                <select class="form-control" id="edit_status" name="is_active">
                                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_notes">Notes</label>
                                <textarea class="form-control" id="edit_notes" name="notes" rows="3">{{ $user->notes }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.global.users.reset-password', $user->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Reset password for <strong>{{ $user->name }}</strong>?</p>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="password" required>
                        <small class="form-text text-muted">Minimum 8 characters</small>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.global.users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        This action cannot be undone. The user will be permanently deleted.
                    </div>
                    <p>Are you sure you want to delete user <strong>{{ $user->name }}</strong>?</p>
                    <div class="form-group">
                        <label for="delete_reason">Reason for Deletion</label>
                        <textarea class="form-control" id="delete_reason" name="reason" rows="3" required 
                                  placeholder="Please provide a reason for deleting this user..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.global.users.send-message', $user->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Send Message to {{ $user->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message_subject">Subject</label>
                        <input type="text" class="form-control" id="message_subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message_content">Message</label>
                        <textarea class="form-control" id="message_content" name="content" rows="5" required 
                                  placeholder="Type your message here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e0e0e0;
}
.timeline-content {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}
.bg-success { background-color: #28a745 !important; }
.bg-info { background-color: #17a2b8 !important; }
.bg-warning { background-color: #ffc107 !important; }
.bg-danger { background-color: #dc3545 !important; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Suspend user
    $('#suspendUserBtn').click(function() {
        if (confirm('Suspend user {{ $user->name }}?')) {
            // Implement suspend functionality
            window.location.href = "{{ route('admin.global.users.suspend', $user->id) }}";
        }
    });

    // Activate user
    $('#activateUserBtn').click(function() {
        if (confirm('Activate user {{ $user->name }}?')) {
            // Implement activate functionality
            window.location.href = "{{ route('admin.global.users.activate', $user->id) }}";
        }
    });

    // Role-based department visibility
    $('#edit_role').change(function() {
        const role = $(this).val();
        const departmentField = $('#edit_department').closest('.form-group');
        
        if (['hod', 'academic_admin', 'finance_admin'].includes(role)) {
            departmentField.show();
        } else {
            departmentField.hide();
        }
    });

    // Initialize role-based visibility
    $('#edit_role').trigger('change');
});
</script>
@endpush