@extends('admin.layouts.master')

@section('title', 'Users Management - Global Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
        <div class="d-flex">
            <button class="btn btn-primary shadow-sm mr-2" data-toggle="modal" data-target="#addUserModal">
                <i class="fas fa-user-plus fa-sm text-white-50"></i> Add User
            </button>
            <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#importUsersModal">
                <i class="fas fa-file-import fa-sm text-white-50"></i> Import Users
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Admin Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['admin_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Student Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['student_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Users</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.global.users') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="role">User Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="">All Roles</option>
                                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="finance_admin" {{ request('role') == 'finance_admin' ? 'selected' : '' }}>Finance Admin</option>
                                <option value="academic_admin" {{ request('role') == 'academic_admin' ? 'selected' : '' }}>Academic Admin</option>
                                <option value="hod" {{ request('role') == 'hod' ? 'selected' : '' }}>Head of Department</option>
                                <option value="global_admin" {{ request('role') == 'global_admin' ? 'selected' : '' }}>Global Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Account Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select class="form-control" id="department" name="department">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Name, Email, or ID">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.global.users') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
            <span class="badge badge-primary">Total: {{ $users->total() }}</span>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <strong class="text-primary">
                                    @if($user->role === 'student')
                                    {{ $user->student_id ?? 'N/A' }}
                                    @else
                                    {{ $user->id }}
                                    @endif
                                </strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <img class="img-profile rounded-circle" 
                                             src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                             width="32" height="32" alt="User Avatar">
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->phone)
                                        <br><small class="text-muted">{{ $user->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-{{ $user->getRoleBadgeColor() }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td>
                                @if($user->department)
                                <span class="badge badge-light">{{ $user->department }}</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if($user->last_login_at)
                                {{ $user->last_login_at->format('M d, Y H:i') }}
                                <br><small class="text-muted">{{ $user->last_login_at->diffForHumans() }}</small>
                                @else
                                <span class="text-muted">Never</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.global.users.view', $user->id) }}" 
                                       class="btn btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <button type="button" class="btn btn-warning edit-user-btn"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if($user->is_active)
                                    <button type="button" class="btn btn-secondary suspend-user-btn"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            title="Suspend User">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-success activate-user-btn"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            title="Activate User">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    @endif

                                    @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-danger delete-user-btn"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                <h4 class="text-gray-500">No Users Found</h4>
                <p class="text-gray-500">There are no users matching your criteria.</p>
                @if(request()->anyFilled(['role', 'status', 'department', 'search']))
                <a href="{{ route('admin.global.users') }}" class="btn btn-primary">
                    <i class="fas fa-redo mr-2"></i> Clear Filters
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.global.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">User Role *</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="student">Student</option>
                                    <option value="admin">Admin</option>
                                    <option value="finance_admin">Finance Admin</option>
                                    <option value="academic_admin">Academic Admin</option>
                                    <option value="hod">Head of Department</option>
                                    <option value="global_admin">Global Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department">Department</label>
                                <select class="form-control" id="department" name="department">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="form-text text-muted">Minimum 8 characters</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="notes">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Any additional notes about this user..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Users Modal -->
<div class="modal fade" id="importUsersModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Users</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Download the template file and fill in user information. Then upload the completed file.
                </div>
                <div class="form-group">
                    <label for="user_import_file">Select CSV File</label>
                    <input type="file" class="form-control-file" id="user_import_file" accept=".csv">
                    <small class="form-text text-muted">File must be in CSV format with proper headers</small>
                </div>
                <div class="text-center">
                    <a href="{{ route('admin.global.users.template') }}" class="btn btn-outline-primary">
                        <i class="fas fa-download mr-2"></i>Download Template
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="importUsersBtn">Import Users</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#usersTable').DataTable({
        "pageLength": 25,
        "order": [[7, 'desc']],
        "language": {
            "search": "Search users:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries"
        }
    });

    // Role-based department visibility
    $('#role').change(function() {
        const role = $(this).val();
        const departmentField = $('#department');
        
        if (['hod', 'academic_admin', 'finance_admin'].includes(role)) {
            departmentField.closest('.form-group').show();
        } else {
            departmentField.closest('.form-group').hide();
        }
    });

    // User action buttons
    $('.edit-user-btn').click(function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        
        // Implement edit functionality
        alert('Edit user: ' + userName);
    });

    $('.suspend-user-btn').click(function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        
        if (confirm('Suspend user ' + userName + '?')) {
            // Implement suspend functionality
            alert('User ' + userName + ' suspended.');
        }
    });

    $('.activate-user-btn').click(function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        
        if (confirm('Activate user ' + userName + '?')) {
            // Implement activate functionality
            alert('User ' + userName + ' activated.');
        }
    });

    $('.delete-user-btn').click(function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        
        if (confirm('Delete user ' + userName + '? This action cannot be undone.')) {
            // Implement delete functionality
            alert('User ' + userName + ' deleted.');
        }
    });

    // Import users
    $('#importUsersBtn').click(function() {
        const fileInput = $('#user_import_file')[0];
        if (fileInput.files.length === 0) {
            alert('Please select a file to import.');
            return;
        }
        
        // Implement import functionality
        alert('Importing users from file...');
        $('#importUsersModal').modal('hide');
    });
});
</script>
@endpush