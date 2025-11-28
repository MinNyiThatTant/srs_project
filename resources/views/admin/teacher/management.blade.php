@extends('admin.layouts.master')

@section('title', 'Teacher Management')
@section('page-title', 'Teacher Management')
@section('page-subtitle', 'Manage Teaching Staff')
@section('breadcrumb', 'Teachers')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Teaching Staff</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Assigned Students</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $teacher)
                    <tr>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->department ?? 'N/A' }}</td>
                        <td>{{ $teacher->position ?? 'Teacher' }}</td>
                        <td>
                            <span class="badge bg-primary">0 students</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <button class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection