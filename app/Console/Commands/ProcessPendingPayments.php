<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessPendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-pending-payments';

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
    $pendingPayments = Payment::where('status', 'pending')->get();
    
    foreach ($pendingPayments as $payment) {
        // Check status with KBZ Pay API
        $status = $this->checkPaymentStatus($payment->transaction_id);
        
        if ($status !== 'pending') {
            $payment->status = $status;
            $payment->save();
            \Log::info("Updated payment: {$payment->transaction_id} to {$status}");
        }
    }
}
}
