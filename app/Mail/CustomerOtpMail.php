<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Customer $customer,
        public string $otp
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('portal.otp_email_subject', ['name' => brand_name()]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
