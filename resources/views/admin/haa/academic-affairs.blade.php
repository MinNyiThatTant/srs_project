@extends('admin.layouts.master')

@section('title', 'Academic Affairs - HAA')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Academic Affairs Management</h1>
        <div class="d-flex">
            <button class="btn btn-primary shadow-sm mr-2" data-toggle="modal" data-target="#addProgramModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add Program
            </button>
            <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#importCoursesModal">
                <i class="fas fa-file-import fa-sm text-white-50"></i> Import Courses
            </button>
        </div>
    </div>

    <!-- Academic Programs -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Academic Programs</h6>
                    <div class="d-flex">
                        <input type="text" class="form-control form-control-sm mr-2" id="programSearch" placeholder="Search programs...">
                        <button class="btn btn-sm btn-outline-primary" id="refreshPrograms">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="programsTable">
                            <thead>
                                <tr>
                                    <th>Program Code</th>
                                    <th>Program Name</th>
                                    <th>Department</th>
                                    <th>Duration</th>
                                    <th>Credits</th>
                                    <th>Status</th>
                                    <th>Students</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($programs as $program)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $program->code }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $program->name }}</strong>
                                        @if($program->description)
                                        <br><small class="text-muted">{{ Str::limit($program->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $program->department }}</span>
                                    </td>
                                    <td>{{ $program->duration }} years</td>
                                    <td>{{ $program->total_credits }} credits</td>
                                    <td>
                                        <span class="badge badge-{{ $program->is_active ? 'success' : 'secondary' }}">
                                            {{ $program->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $program->current_students }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info view-program-btn"
                                                    data-program-id="{{ $program->id }}"
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning edit-program-btn"
                                                    data-program-id="{{ $program->id }}"
                                                    title="Edit Program">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($program->is_active)
                                            <button class="btn btn-secondary deactivate-program-btn"
                                                    data-program-id="{{ $program->id }}"
                                                    data-program-name="{{ $program->name }}"
                                                    title="Deactivate Program">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                            @else
                                            <button class="btn btn-success activate-program-btn"
                                                    data-program-id="{{ $program->id }}"
                                                    data-program-name="{{ $program->name }}"
                                                    title="Activate Program">
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
        </div>
    </div>

    <!-- Faculty Management -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Faculty Members</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="facultyTable">
                            <thead>
                                <tr>
                                    <th>Faculty ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Courses</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($faculty as $member)
                                <tr>
                                    <td>
                                        <strong>{{ $member->faculty_id }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $member->name }}</strong>
                                        <br><small class="text-muted">{{ $member->qualification }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $member->department }}</span>
                                    </td>
                                    <td>{{ $member->position }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $member->is_active ? 'success' : 'secondary' }}">
                                            {{ $member->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $member->assigned_courses_count }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info view-faculty-btn"
                                                    data-faculty-id="{{ $member->id }}"
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning assign-courses-btn"
                                                    data-faculty-id="{{ $member->id }}"
                                                    data-faculty-name="{{ $member->name }}"
                                                    title="Assign Courses">
                                                <i class="fas fa-book"></i>
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

    <!-- Academic Calendar -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Academic Calendar</h6>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEventModal">
                        <i class="fas fa-plus"></i> Add Event
                    </button>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="addProgramForm" method="POST" action="{{ route('admin.haa.programs.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Academic Program</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="program_code">Program Code *</label>
                                <input type="text" class="form-control" id="program_code" name="code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="program_name">Program Name *</label>
                                <input type="text" class="form-control" id="program_name" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="program_department">Department *</label>
                                <select class="form-control" id="program_department" name="department" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="program_duration">Duration (Years) *</label>
                                <select class="form-control" id="program_duration" name="duration" required>
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                    <option value="4" selected>4 Years</option>
                                    <option value="5">5 Years</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_credits">Total Credits *</label>
                                <input type="number" class="form-control" id="total_credits" name="total_credits" 
                                       min="1" max="200" value="120" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="program_type">Program Type</label>
                                <select class="form-control" id="program_type" name="type">
                                    <option value="undergraduate">Undergraduate</option>
                                    <option value="graduate">Graduate</option>
                                    <option value="postgraduate">Postgraduate</option>
                                    <option value="diploma">Diploma</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="program_description">Description</label>
                        <textarea class="form-control" id="program_description" name="description" 
                                  rows="3" placeholder="Program description..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="admission_requirements">Admission Requirements</label>
                        <textarea class="form-control" id="admission_requirements" name="admission_requirements" 
                                  rows="3" placeholder="Minimum requirements for admission..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Program</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addEventForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Academic Event</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="event_title">Event Title *</label>
                        <input type="text" class="form-control" id="event_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="event_type">Event Type</label>
                        <select class="form-control" id="event_type" name="type">
                            <option value="academic">Academic</option>
                            <option value="holiday">Holiday</option>
                            <option value="exam">Examination</option>
                            <option value="meeting">Meeting</option>
                            <option value="workshop">Workshop</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_start">Start Date *</label>
                                <input type="datetime-local" class="form-control" id="event_start" name="start" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event_end">End Date *</label>
                                <input type="datetime-local" class="form-control" id="event_end" name="end" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event_description">Description</label>
                        <textarea class="form-control" id="event_description" name="description" 
                                  rows="3" placeholder="Event description..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
$(document).ready(function() {
    // Initialize DataTables
    $('#programsTable').DataTable({
        "pageLength": 25,
        "order": [[0, 'asc']]
    });

    $('#facultyTable').DataTable({
        "pageLength": 25,
        "order": [[0, 'asc']]
    });

    // Program search
    $('#programSearch').on('keyup', function() {
        $('#programsTable').DataTable().search(this.value).draw();
    });

    // Initialize calendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: {!! json_encode($calendarEvents) !!},
        eventClick: function(info) {
            alert('Event: ' + info.event.title + '\n' +
                  'Start: ' + info.event.start.toLocaleString());
        }
    });
    calendar.render();

    // Program actions
    $('.deactivate-program-btn').click(function() {
        const programId = $(this).data('program-id');
        const programName = $(this).data('program-name');
        
        if (confirm('Deactivate program: ' + programName + '?')) {
            // Implement deactivation
            alert('Program deactivated: ' + programName);
        }
    });

    $('.activate-program-btn').click(function() {
        const programId = $(this).data('program-id');
        const programName = $(this).data('program-name');
        
        if (confirm('Activate program: ' + programName + '?')) {
            // Implement activation
            alert('Program activated: ' + programName);
        }
    });

    // Add event form
    $('#addEventForm').submit(function(e) {
        e.preventDefault();
        alert('Event added successfully!');
        $('#addEventModal').modal('hide');
        calendar.refetchEvents();
    });
});
</script>
@endpush