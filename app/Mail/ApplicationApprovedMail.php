<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $approvalType;
    public $approvedBy;
    public $nextSteps;
    public $statusUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Application $application, string $approvalType, string $approvedBy, ?string $nextSteps = null)
    {
        $this->application = $application;
        $this->approvalType = $approvalType;
        $this->approvedBy = $approvedBy;
        $this->nextSteps = $nextSteps;
        $this->statusUrl = url('/applications/' . $application->id . '/status');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Approved - ' . $this->approvalType . ' - WYTU',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application-approved',
            with: [
                'application' => $this->application,
                'approvalType' => $this->approvalType,
                'approvedBy' => $this->approvedBy,
                'nextSteps' => $this->nextSteps,
                'approvalDate' => now()->format('F d, Y'),
                'statusUrl' => $this->statusUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}