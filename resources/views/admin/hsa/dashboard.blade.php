@extends('admin.layouts.master')

@section('title', 'HSA Dashboard - Head of Student Affairs')
@section('page-title', 'Student Affairs Dashboard')
@section('page-subtitle', 'Student Management & Administration')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Total Students</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_students'] }}</h4>
                            <p class="text-success small mb-0">
                                <i class="bi bi-arrow-up"></i> {{ $stats['student_growth'] }}% this month
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-primary">
                            <i class="bi bi-people-fill text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Active Applications</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['active_applications'] }}</h4>
                            <p class="text-warning small mb-0">
                                <i class="bi bi-clock"></i> {{ $stats['pending_review'] }} need review
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-success">
                            <i class="bi bi-file-earmark-text text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Pending Issues</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['pending_issues'] }}</h4>
                            <p class="text-danger small mb-0">
                                <i class="bi bi-exclamation-triangle"></i> {{ $stats['urgent_issues'] }} urgent
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-warning">
                            <i class="bi bi-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Resolved Today</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['resolved_today'] }}</h4>
                            <p class="text-info small mb-0">
                                <i class="bi bi-check-all"></i> {{ $stats['resolution_rate'] }}% success rate
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-info">
                            <i class="bi bi-check-circle text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Student Activities & Recent Applications -->
    <div class="col-12 col-lg-8">
        <div class="row">
            <!-- Recent Student Activities -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Student Activities</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Activity Type</th>
                                        <th>Department</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['recent_activities'] as $activity)
                                    <tr>
                                        <td>
                                            @if($activity->user)
                                                {{ $activity->user->student_id ?? 'N/A' }}
                                            @else
                                                {{ $activity->application_id }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->user)
                                                {{ $activity->user->name }}
                                            @else
                                                {{ $activity->name }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi 
                                                    @if(str_contains(strtolower($activity->activity_type), 'application')) bi-file-earmark-text 
                                                    @elseif(str_contains(strtolower($activity->activity_type), 'payment')) bi-credit-card 
                                                    @elseif(str_contains(strtolower($activity->activity_type), 'registration')) bi-person-plus 
                                                    @else bi-activity 
                                                    @endif
                                                "></i>
                                                {{ $activity->activity_type }}
                                            </span>
                                        </td>
                                        <td>{{ $activity->department ?? 'N/A' }}</td>
                                        <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $activity->status_color ?? 'primary' }}">
                                                {{ ucfirst($activity->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                            No recent activities found
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon icon-lg rounded-circle bg-primary mb-3">
                            <i class="bi bi-person-plus text-white"></i>
                        </div>
                        <h5>Student Registration</h5>
                        <p class="text-muted">Manage student enrollments and registrations</p>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary btn-sm">Manage Students</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon icon-lg rounded-circle bg-success mb-3">
                            <i class="bi bi-file-earmark-check text-white"></i>
                        </div>
                        <h5>Application Review</h5>
                        <p class="text-muted">Review and process student applications</p>
                        <a href="{{ route('admin.applications.all') }}" class="btn btn-outline-success btn-sm">View Applications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar - Stats & Quick Actions -->
    <div class="col-12 col-lg-4">
        <!-- Department Statistics -->
        <div class="card">
            <div class="card-header">
                <h4>Department Overview</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Computer Engineering</span>
                        <span class="fw-bold">{{ $stats['department_stats']['ceit'] ?? 0 }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: {{ ($stats['department_stats']['ceit'] ?? 0) / max(array_sum($stats['department_stats']), 1) * 100 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Civil Engineering</span>
                        <span class="fw-bold">{{ $stats['department_stats']['civil'] ?? 0 }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ ($stats['department_stats']['civil'] ?? 0) / max(array_sum($stats['department_stats']), 1) * 100 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Electronics</span>
                        <span class="fw-bold">{{ $stats['department_stats']['electronics'] ?? 0 }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: {{ ($stats['department_stats']['electronics'] ?? 0) / max(array_sum($stats['department_stats']), 1) * 100 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Mechanical</span>
                        <span class="fw-bold">{{ $stats['department_stats']['mechanical'] ?? 0 }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: {{ ($stats['department_stats']['mechanical'] ?? 0) / max(array_sum($stats['department_stats']), 1) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h4>Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-people me-2"></i> Manage Students
                    </a>
                    <a href="{{ route('admin.applications.all') }}" class="btn btn-outline-success text-start">
                        <i class="bi bi-files me-2"></i> View Applications
                    </a>
                    <a href="{{ route('admin.staff.management') }}" class="btn btn-outline-info text-start">
                        <i class="bi bi-person-badge me-2"></i> Staff Management
                    </a>
                    <a href="#" class="btn btn-outline-warning text-start">
                        <i class="bi bi-megaphone me-2"></i> Announcements
                    </a>
                    <a href="#" class="btn btn-outline-dark text-start">
                        <i class="bi bi-graph-up me-2"></i> Generate Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- System Alerts -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>System Alerts</h4>
                <span class="badge bg-danger">{{ $stats['alerts_count'] ?? 0 }}</span>
            </div>
            <div class="card-body">
                @forelse($stats['system_alerts'] as $alert)
                <div class="alert alert-{{ $alert['type'] }} alert-dismissible show mb-2" role="alert">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="bi 
                                @if($alert['type'] == 'danger') bi-exclamation-triangle-fill
                                @elseif($alert['type'] == 'warning') bi-exclamation-circle-fill
                                @else bi-info-circle-fill
                                @endif
                            "></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="alert-heading mb-1">{{ $alert['title'] }}</h6>
                            <p class="mb-0 small">{{ $alert['message'] }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="bi bi-check-circle display-6 text-success mb-2"></i>
                    <p class="mb-0">No active alerts</p>
                    <small>All systems are running smoothly</small>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="card">
            <div class="card-header">
                <h4>Recent Notifications</h4>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($stats['recent_notifications'] as $notification)
                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm 
                                    @if($notification['type'] == 'success') bg-success 
                                    @elseif($notification['type'] == 'warning') bg-warning 
                                    @elseif($notification['type'] == 'danger') bg-danger 
                                    @else bg-primary 
                                    @endif
                                ">
                                    <i class="bi 
                                        @if($notification['icon'] == 'payment') bi-credit-card 
                                        @elseif($notification['icon'] == 'application') bi-file-earmark-text 
                                        @elseif($notification['icon'] == 'user') bi-person 
                                        @else bi-bell 
                                        @endif
                                    text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $notification['title'] }}</h6>
                                <p class="text-muted mb-0 small">{{ $notification['message'] }}</p>
                                <small class="text-muted">{{ $notification['time'] }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-bell-slash display-6 d-block mb-2"></i>
                        <p class="mb-0">No new notifications</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Application Status Distribution</h4>
            </div>
            <div class="card-body">
                <canvas id="applicationStatusChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Monthly Student Registration</h4>
            </div>
            <div class="card-body">
                <canvas id="monthlyRegistrationChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Application Status Chart
        const statusCtx = document.getElementById('applicationStatusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Under Review', 'Approved', 'Rejected', 'Payment Pending'],
                datasets: [{
                    data: [25, 15, 40, 5, 15],
                    backgroundColor: [
                        '#ffc107',
                        '#17a2b8',
                        '#28a745',
                        '#dc3545',
                        '#6c757d'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw}%`;
                            }
                        }
                    }
                }
            }
        });

        // Monthly Registration Chart
        const monthlyCtx = document.getElementById('monthlyRegistrationChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Student Registrations',
                    data: [65, 59, 80, 81, 56, 55, 40, 70, 85, 92, 78, 82],
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                }
            }
        });

        // Auto-refresh dashboard data every 30 seconds
        setInterval(function() {
            fetch('{{ route("admin.hsa.dashboard") }}')
                .then(response => response.json())
                .then(data => {
                    // Update statistics cards
                    document.querySelector('[data-stat="total-students"]').textContent = data.total_students;
                    document.querySelector('[data-stat="active-applications"]').textContent = data.active_applications;
                    // Add more updates as needed
                })
                .catch(error => console.error('Error refreshing dashboard:', error));
        }, 30000);

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Quick status update function
    function updateApplicationStatus(applicationId, status) {
        if (confirm(`Are you sure you want to mark this application as ${status}?`)) {
            fetch(`/admin/applications/${applicationId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating application status');
            });
        }
    }
</script>
@endpush

@push('styles')
<style>
    .card-stat {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
    }
    
    .stats h4 {
        font-weight: 700;
        color: #2c3e50;
    }
    
    .progress {
        background-color: #e9ecef;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .list-group-item {
        border: none;
        transition: background-color 0.2s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    
    .alert {
        border: none;
        border-left: 4px solid;
    }
    
    .alert-danger { border-left-color: #dc3545; }
    .alert-warning { border-left-color: #ffc107; }
    .alert-info { border-left-color: #17a2b8; }
    .alert-success { border-left-color: #28a745; }
</style>
@endpush