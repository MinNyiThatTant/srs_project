<!DOCTYPE html>
<html>
<head>
    <title>Debug Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="10">
</head>
<body>
    <div class="container-fluid mt-4">
        <h2>Debug Dashboard</h2>
        
        <!-- Payment Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5>Total Payments</h5>
                        <h2>{{ $paymentStats['total'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5>Completed</h5>
                        <h2>{{ $paymentStats['completed'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5>Pending</h5>
                        <h2>{{ $paymentStats['pending'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5>Failed</h5>
                        <h2>{{ $paymentStats['failed'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Recent Payments</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Application</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Method</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                            <tr>
                                <td><code>{{ $payment->transaction_id }}</code></td>
                                <td>{{ $payment->application->application_id }}</td>
                                <td>{{ number_format($payment->amount) }} MMK</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>{{ $payment->created_at->format('H:i:s') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Logs -->
        <div class="card">
            <div class="card-header">
                <h5>Recent Logs (Last 50 lines)</h5>
            </div>
            <div class="card-body">
                <pre style="max-height: 400px; overflow-y: auto; background: #f8f9fa; padding: 10px; font-size: 12px;">{{ $logs }}</pre>
            </div>
        </div>
    </div>
</body>
</html>