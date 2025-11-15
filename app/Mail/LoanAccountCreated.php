<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class LoanAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $username;
    public $password;
    public $loginUrl;
    public $applicationType;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $userEmail, $username, $password, $loginUrl, $applicationType = 'Loan')
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->username = $username;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
        $this->applicationType = $applicationType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [new Address(config('mail.from.address'), config('mail.from.name'))],
            subject: 'Your Account Has Been Created - ' . $this->userName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.loan_account_created',
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

