@extends('admin.layouts.master')
@section('title', 'Payment Verified Applications')
@section('content')
<div class="card">
    <div class="card-header">
        <h4>Payment Verified Applications - Ready for Approval</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Payment Verified At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr>
                    <td>{{ $application->application_id }}</td>
                    <td>{{ $application->name }}</td>
                    <td>{{ $application->department }}</td>
                    <td>{{ $application->payment_verified_at->format('M d, Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.finance.application.view', $application->id) }}" class="btn btn-info btn-sm">View</a>
                        <form action="{{ route('admin.finance.approve-application', $application->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $application->id }}">Reject</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $applications->links() }}
    </div>
</div>
@endsection