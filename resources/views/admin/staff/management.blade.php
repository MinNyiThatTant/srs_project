@extends('admin.layouts.master')

@section('title', 'Staff Management')
@section('page-title', 'Staff Management')
@section('page-subtitle', 'Manage All Staff Members')
@section('breadcrumb', 'Staff')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Staff Members</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $member->role)) }}</span>
                        </td>
                        <td>{{ $member->department ?? 'N/A' }}</td>
                        <td>{{ $member->position ?? 'N/A' }}</td>
                        <td>
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