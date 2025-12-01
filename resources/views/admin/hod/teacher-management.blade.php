@extends('admin.layouts.master')

@section('title', 'Teacher Management - Global Admin')
@section('page-title', 'Teacher Management')
@section('page-subtitle', 'All Teachers Administration')
@section('breadcrumb', 'Teacher Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>All Teachers</h4>
                <div class="card-header-action">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">PDF</a></li>
                            <li><a class="dropdown-item" href="#">Excel</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="teachersTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Teacher ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>HOD</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $teacher->staff_id }}</td>
                                <td>{{ $teacher->name }}</td>
                                <td>{{ $teacher->email }}</td>
                                <td>{{ $teacher->department }}</td>
                                <td>{{ $teacher->position }}</td>
                                <td>
                                    <span class="badge {{ $teacher->is_hod ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $teacher->is_hod ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $teacher->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($teacher->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewTeacherModal"
                                                data-id="{{ $teacher->id }}"
                                                data-name="{{ $teacher->name }}"
                                                data-email="{{ $teacher->email }}"
                                                data-department="{{ $teacher->department }}"
                                                data-position="{{ $teacher->position }}"
                                                data-ishod="{{ $teacher->is_hod }}"
                                                data-status="{{ $teacher->status }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editTeacherModal"
                                                data-id="{{ $teacher->id }}"
                                                data-name="{{ $teacher->name }}"
                                                data-email="{{ $teacher->email }}"
                                                data-department="{{ $teacher->department }}"
                                                data-position="{{ $teacher->position }}"
                                                data-ishod="{{ $teacher->is_hod }}"
                                                data-status="{{ $teacher->status }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
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

<!-- View Teacher Modal -->
<div class="modal fade" id="viewTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Teacher Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <strong>Name:</strong>
                        <div id="view_name" class="mt-1"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Email:</strong>
                        <div id="view_email" class="mt-1"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Department:</strong>
                        <div id="view_department" class="mt-1"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Position:</strong>
                        <div id="view_position" class="mt-1"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Head of Department:</strong>
                        <div id="view_ishod" class="mt-1"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Status:</strong>
                        <div id="view_status" class="mt-1"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Teacher Modal -->
<div class="modal fade" id="editTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editTeacherForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_teacher_name">Full Name</label>
                                <input type="text" class="form-control" id="edit_teacher_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_teacher_email">Email</label>
                                <input type="email" class="form-control" id="edit_teacher_email" name="email" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_teacher_department">Department</label>
                                <input type="text" class="form-control" id="edit_teacher_department" name="department" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_teacher_position">Position</label>
                                <select class="form-control" id="edit_teacher_position" name="position" required>
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
                                <label for="edit_teacher_ishod">Head of Department</label>
                                <select class="form-control" id="edit_teacher_ishod" name="is_hod" required>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_teacher_status">Status</label>
                                <select class="form-control" id="edit_teacher_status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize DataTable
    let teachersTable = new simpleDatatables.DataTable("#teachersTable", {
        searchable: true,
        sortable: true,
        perPage: 10
    });

    // View Teacher Modal Handler
    const viewTeacherModal = document.getElementById('viewTeacherModal');
    viewTeacherModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        
        document.getElementById('view_name').textContent = button.getAttribute('data-name');
        document.getElementById('view_email').textContent = button.getAttribute('data-email');
        document.getElementById('view_department').textContent = button.getAttribute('data-department');
        document.getElementById('view_position').textContent = button.getAttribute('data-position');
        document.getElementById('view_ishod').textContent = button.getAttribute('data-ishod') === '1' ? 'Yes' : 'No';
        document.getElementById('view_status').textContent = button.getAttribute('data-status');
    });

    // Edit Teacher Modal Handler
    const editTeacherModal = document.getElementById('editTeacherModal');
    editTeacherModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const teacherId = button.getAttribute('data-id');

        document.getElementById('edit_teacher_name').value = button.getAttribute('data-name');
        document.getElementById('edit_teacher_email').value = button.getAttribute('data-email');
        document.getElementById('edit_teacher_department').value = button.getAttribute('data-department');
        document.getElementById('edit_teacher_position').value = button.getAttribute('data-position');
        document.getElementById('edit_teacher_ishod').value = button.getAttribute('data-ishod');
        document.getElementById('edit_teacher_status').value = button.getAttribute('data-status');

        // Update form action
        document.getElementById('editTeacherForm').action = `/admin/teachers/${teacherId}`;
    });
</script>
@endpush