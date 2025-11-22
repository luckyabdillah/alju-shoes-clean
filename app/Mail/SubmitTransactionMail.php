<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmitTransactionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $transaction;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $transaction)
    {
        $this->name = $name;
        $this->transaction = $transaction;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order: ' . $this->transaction->invoice_no,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return (new Content)
                    ->markdown('emails.submit')
                    ->with([
                        'name' => $this->name,
                        'transaction' => $this->transaction,
                    ]);
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
