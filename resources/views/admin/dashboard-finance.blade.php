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
                            <h6 class="text-muted">Recent Transactions</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['recent_payments']->count() }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-primary">
                            <i class="bi bi-list-check text-white"></i>
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
                <h4>Recent Payments</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Application</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_payments'] as $payment)
                            <tr>
                                <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                                <td>{{ $payment->application->name ?? 'N/A' }}</td>
                                <td>{{ number_format($payment->amount) }} MMK</td>
                                <td>{{ strtoupper($payment->payment_method) }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($payment->status === 'completed')
                                    <a href="{{ route('admin.applications.verify-payment', $payment->application->id ?? '#') }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        Verify
                                    </a>
                                    @endif
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
@endsection