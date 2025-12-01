@extends('admin.layouts.master')

@section('title', 'Staff Management - HOD')
@section('page-title', 'Staff Management')
@section('page-subtitle', 'Department Staff Administration')
@section('breadcrumb', 'Staff Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Department Staff List</h4>
                <div class="card-header-action">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                        <i class="bi bi-plus-circle"></i> Add Staff
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="staffTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff as $member)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $member->staff_id }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->department }}</td>
                                <td>{{ $member->position }}</td>
                                <td>
                                    <span class="badge {{ $member->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editStaffModal"
                                                data-id="{{ $member->id }}"
                                                data-name="{{ $member->name }}"
                                                data-email="{{ $member->email }}"
                                                data-position="{{ $member->position }}"
                                                data-status="{{ $member->status }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('hod.staff.destroy', $member->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hod.staff.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="position">Position</label>
                                <select class="form-control" id="position" name="position" required>
                                    <option value="">Select Position</option>
                                    <option value="lecturer">Lecturer</option>
                                    <option value="senior_lecturer">Senior Lecturer</option>
                                    <option value="assistant_professor">Assistant Professor</option>
                                    <option value="associate_professor">Associate Professor</option>
                                    <option value="professor">Professor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editStaffForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_name">Full Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_position">Position</label>
                                <select class="form-control" id="edit_position" name="position" required>
                                    <option value="">Select Position</option>
                                    <option value="lecturer">Lecturer</option>
                                    <option value="senior_lecturer">Senior Lecturer</option>
                                    <option value="assistant_professor">Assistant Professor</option>
                                    <option value="associate_professor">Associate Professor</option>
                                    <option value="professor">Professor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_status">Status</label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize DataTable
    let staffTable = new simpleDatatables.DataTable("#staffTable", {
        searchable: true,
        sortable: true,
        perPage: 10
    });

    // Edit Staff Modal Handler
    const editStaffModal = document.getElementById('editStaffModal');
    editStaffModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const staffId = button.getAttribute('data-id');
        const staffName = button.getAttribute('data-name');
        const staffEmail = button.getAttribute('data-email');
        const staffPosition = button.getAttribute('data-position');
        const staffStatus = button.getAttribute('data-status');

        document.getElementById('edit_name').value = staffName;
        document.getElementById('edit_email').value = staffEmail;
        document.getElementById('edit_position').value = staffPosition;
        document.getElementById('edit_status').value = staffStatus;

        // Update form action
        document.getElementById('editStaffForm').action = `/hod/staff/${staffId}`;
    });
</script>
@endpush