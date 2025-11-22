@extends('admin.layouts.master')

@section('title', 'Course Management - HAA')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Course Management</h1>
        <div class="d-flex">
            <button class="btn btn-primary shadow-sm mr-2" data-toggle="modal" data-target="#addCourseModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add Course
            </button>
            <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#bulkImportModal">
                <i class="fas fa-file-import fa-sm text-white-50"></i> Bulk Import
            </button>
        </div>
    </div>

    <!-- Course Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Courses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_courses'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
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
                                Active Courses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_courses'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Departments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_departments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
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
                                Avg. Credits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['avg_credits'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Course Filters</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="department_filter">Department</label>
                        <select class="form-control" id="department_filter">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="program_filter">Program</label>
                        <select class="form-control" id="program_filter">
                            <option value="">All Programs</option>
                            @foreach($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="year_filter">Academic Year</label>
                        <select class="form-control" id="year_filter">
                            <option value="">All Years</option>
                            <option value="1">First Year</option>
                            <option value="2">Second Year</option>
                            <option value="3">Third Year</option>
                            <option value="4">Fourth Year</option>
                            <option value="5">Fifth Year</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status_filter">Status</label>
                        <select class="form-control" id="status_filter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Course Catalog</h6>
            <div class="d-flex">
                <input type="text" class="form-control form-control-sm mr-2" id="courseSearch" placeholder="Search courses...">
                <button class="btn btn-sm btn-outline-primary" id="refreshCourses">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="coursesTable">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Department</th>
                            <th>Program</th>
                            <th>Credits</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Faculty</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $course->code }}</strong>
                            </td>
                            <td>
                                <strong>{{ $course->name }}</strong>
                                @if($course->description)
                                <br><small class="text-muted">{{ Str::limit($course->description, 60) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light">{{ $course->department }}</span>
                            </td>
                            <td>{{ $course->program->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $course->credits }} credits</span>
                            </td>
                            <td>Year {{ $course->academic_year }}</td>
                            <td>Semester {{ $course->semester }}</td>
                            <td>
                                @if($course->faculty)
                                <span class="badge badge-secondary">{{ $course->faculty->name }}</span>
                                @else
                                <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $course->is_active ? 'success' : 'secondary' }}">
                                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info view-course-btn"
                                            data-course-id="{{ $course->id }}"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning edit-course-btn"
                                            data-course-id="{{ $course->id }}"
                                            title="Edit Course">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-primary assign-faculty-btn"
                                            data-course-id="{{ $course->id }}"
                                            data-course-name="{{ $course->name }}"
                                            title="Assign Faculty">
                                        <i class="fas fa-user-tie"></i>
                                    </button>
                                    @if($course->is_active)
                                    <button class="btn btn-secondary deactivate-course-btn"
                                            data-course-id="{{ $course->id }}"
                                            data-course-name="{{ $course->name }}"
                                            title="Deactivate Course">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                    @else
                                    <button class="btn btn-success activate-course-btn"
                                            data-course-id="{{ $course->id }}"
                                            data-course-name="{{ $course->name }}"
                                            title="Activate Course">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Course Prerequisites -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Course Prerequisites</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Prerequisite Courses</th>
                            <th>Minimum Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prerequisites as $prereq)
                        <tr>
                            <td>
                                <strong>{{ $prereq->course->code }}</strong> - {{ $prereq->course->name }}
                            </td>
                            <td>
                                @foreach($prereq->prerequisites as $precourse)
                                <span class="badge badge-primary mr-1">{{ $precourse->code }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge badge-warning">{{ $prereq->minimum_grade ?? 'C' }}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-prereq-btn"
                                        data-course-id="{{ $prereq->course_id }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="addCourseForm" method="POST" action="{{ route('admin.haa.courses.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Course</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_code">Course Code *</label>
                                <input type="text" class="form-control" id="course_code" name="code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_name">Course Name *</label>
                                <input type="text" class="form-control" id="course_name" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_department">Department *</label>
                                <select class="form-control" id="course_department" name="department" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_program">Program *</label>
                                <select class="form-control" id="course_program" name="program_id" required>
                                    <option value="">Select Program</option>
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course_credits">Credits *</label>
                                <input type="number" class="form-control" id="course_credits" name="credits" 
                                       min="1" max="10" value="3" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="academic_year">Academic Year *</label>
                                <select class="form-control" id="academic_year" name="academic_year" required>
                                    <option value="1">First Year</option>
                                    <option value="2">Second Year</option>
                                    <option value="3">Third Year</option>
                                    <option value="4">Fourth Year</option>
                                    <option value="5">Fifth Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="semester">Semester *</label>
                                <select class="form-control" id="semester" name="semester" required>
                                    <option value="1">Semester 1</option>
                                    <option value="2">Semester 2</option>
                                    <option value="3">Semester 3</option>
                                    <option value="4">Semester 4</option>
                                    <option value="5">Semester 5</option>
                                    <option value="6">Semester 6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="course_description">Course Description</label>
                        <textarea class="form-control" id="course_description" name="description" 
                                  rows="3" placeholder="Course description..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="learning_outcomes">Learning Outcomes</label>
                        <textarea class="form-control" id="learning_outcomes" name="learning_outcomes" 
                                  rows="3" placeholder="What students will learn..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="assessment_methods">Assessment Methods</label>
                        <textarea class="form-control" id="assessment_methods" name="assessment_methods" 
                                  rows="2" placeholder="Exams, assignments, projects..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Faculty Modal -->
<div class="modal fade" id="assignFacultyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="assignFacultyForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Faculty to Course</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Assign faculty to: <strong id="assignCourseName"></strong></p>
                    <div class="form-group">
                        <label for="faculty_member">Select Faculty *</label>
                        <select class="form-control" id="faculty_member" name="faculty_id" required>
                            <option value="">Select Faculty Member</option>
                            @foreach($faculty as $member)
                            <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->department }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Faculty</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#coursesTable').DataTable({
        "pageLength": 25,
        "order": [[0, 'asc']]
    });

    // Course filters
    $('#department_filter, #program_filter, #year_filter, #status_filter').change(function() {
        const table = $('#coursesTable').DataTable();
        table.draw();
    });

    // Course search
    $('#courseSearch').on('keyup', function() {
        $('#coursesTable').DataTable().search(this.value).draw();
    });

    // Assign faculty
    $('.assign-faculty-btn').click(function() {
        const courseId = $(this).data('course-id');
        const courseName = $(this).data('course-name');
        
        $('#assignCourseName').text(courseName);
        $('#assignFacultyForm').attr('action', "{{ url('admin/courses') }}/" + courseId + "/assign-faculty");
        $('#assignFacultyModal').modal('show');
    });

    // Course activation/deactivation
    $('.deactivate-course-btn').click(function() {
        const courseId = $(this).data('course-id');
        const courseName = $(this).data('course-name');
        
        if (confirm('Deactivate course: ' + courseName + '?')) {
            // Implement deactivation
            alert('Course deactivated: ' + courseName);
        }
    });

    $('.activate-course-btn').click(function() {
        const courseId = $(this).data('course-id');
        const courseName = $(this).data('course-name');
        
        if (confirm('Activate course: ' + courseName + '?')) {
            // Implement activation
            alert('Course activated: ' + courseName);
        }
    });

    // DataTable custom filtering
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            const department = $('#department_filter').val();
            const program = $('#program_filter').val();
            const year = $('#year_filter').val();
            const status = $('#status_filter').val();

            const rowDepartment = data[2];
            const rowProgram = data[3];
            const rowYear = data[5].replace('Year ', '');
            const rowStatus = data[8].toLowerCase();

            if (department && rowDepartment !== department) return false;
            if (program && rowProgram !== program) return false;
            if (year && rowYear !== year) return false;
            if (status && !rowStatus.includes(status)) return false;

            return true;
        }
    );
});
</script>
@endpush