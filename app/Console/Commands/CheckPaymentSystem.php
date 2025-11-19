<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPaymentSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payment-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $this->info('=== Payment System Status Check ===');
    
    // Check database
    $paymentCount = \App\Models\Payment::count();
    $this->info("Payments in database: {$paymentCount}");
    
    // Check applications needing payment
    $pendingApps = \App\Models\Application::where('payment_status', 'pending')->count();
    $this->info("Applications pending payment: {$pendingApps}");
    
    // Check recent payments
    $recentPayments = \App\Models\Payment::with('application')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
        
    $this->info("\nRecent Payments:");
    foreach ($recentPayments as $payment) {
        $this->line(" - {$payment->transaction_id} | {$payment->status} | {$payment->application->name}");
    }
    
    $this->info('=== Check Complete ===');
}
}
