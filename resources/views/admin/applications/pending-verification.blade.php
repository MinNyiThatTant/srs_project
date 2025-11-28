@extends('admin.layouts.master')
@section('title', 'Pending Payment Verifications')
@section('content')
<div class="card">
    <div class="card-header">
        <h4>Applications Pending Payment Verification</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Payment Amount</th>
                    <th>Payment Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr>
                    <td>{{ $application->application_id }}</td>
                    <td>{{ $application->name }}</td>
                    <td>{{ $application->email }}</td>
                    <td>{{ number_format($application->payments->first()->amount) }} MMK</td>
                    <td>{{ $application->payments->first()->paid_at->format('M d, Y') }}</td>
                    <td>
                        <form action="{{ route('admin.finance.verify-payment', $application->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Verify Payment</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $applications->links() }}
    </div>
</div>
@endsection