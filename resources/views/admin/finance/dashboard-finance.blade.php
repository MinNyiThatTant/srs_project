@extends('admin.layouts.master')

@section('title', 'Finance Admin Dashboard')
@section('page-title', 'Finance Dashboard')
@section('page-subtitle', 'Payment Management & Verification')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Total Payments</h6>
                            <h4 class="font-extrabold mb-0">{{ number_format($stats['total_payments']) }} MMK</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-success">
                            <i class="bi bi-currency-dollar text-white"></i>
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
                            <h6 class="text-muted">Pending Verification</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['pending_verifications'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-warning">
                            <i class="bi bi-clock-history text-white"></i>
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
                            <h6 class="text-muted">Today's Payments</h6>
                            <h4 class="font-extrabold mb-0">{{ number_format($stats['today_payments']) }} MMK</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-info">
                            <i class="bi bi-calendar-day text-white"></i>
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
                            <h6 class="text-muted">Verified Today</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['verified_today'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-primary">
                            <i class="bi bi-check-circle text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Applications Pending Payment Verification</h4>
                <p class="text-muted mb-0">Applications with completed payments waiting for verification</p>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Application ID</th>
                                <th>Student Name</th>
                                <th>Department</th>
                                <th>Payment Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            @php
                                $latestPayment = $application->payments->first();
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $application->application_id }}</strong>
                                </td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->department }}</td>
                                <td>
                                    @if($latestPayment)
                                        {{ number_format($latestPayment->amount) }} MMK
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($latestPayment)
                                        <span class="badge bg-light text-dark">
                                            {{ strtoupper($latestPayment->payment_method) }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($latestPayment)
                                        {{ $latestPayment->created_at->format('M d, Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-warning">Pending Verification</span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.applications.verify-payment', $application->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('Verify payment for {{ $application->name }}? This will move the application to academic review.')">
                                            <i class="bi bi-check-circle me-1"></i> Verify Payment
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.applications.view', $application->id) }}" 
                                       class="btn btn-sm btn-outline-primary ms-1">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $applications->links() }}
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    No applications pending payment verification.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Recent Payment Transactions</h4>
                <p class="text-muted mb-0">Latest completed payment transactions</p>
            </div>
            <div class="card-body">
                @if($stats['recent_payments']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Application</th>
                                <th>Student Name</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_payments'] as $payment)
                            <tr>
                                <td>
                                    <strong>{{ $payment->transaction_id ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    @if($payment->application)
                                        {{ $payment->application->application_id ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($payment->application)
                                        {{ $payment->application->name ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ number_format($payment->amount) }} MMK</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ strtoupper($payment->payment_method ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    No recent payment transactions found.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection