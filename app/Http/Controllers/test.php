<?

public function applicationSuccess($id)
{
    try {
        Log::info('Application success method called', ['id' => $id]);
        
        // Find application by primary key (id)
        $application = Application::findOrFail($id);
        
        Log::info('Application found for success page', [
            'application_id' => $application->id,
            'display_id' => $application->application_id,
            'status' => $application->status
        ]);

        // Check if we should redirect directly to payment
        if ($application->status === 'payment_pending' && $application->payment_status === 'pending') {
            Log::info('Redirecting directly to payment page from success');
            return redirect()->route('payment.show', $application->id);
        }

        return view('application.success', compact('application'));
        
    } catch (\Exception $e) {
        Log::error('Application success page error: ' . $e->getMessage(), ['id' => $id]);
        return redirect('/')->with('error', 'Application not found.');
    }
}