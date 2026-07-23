<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $isConfirmation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking, $isConfirmation = false)
    {
        $this->booking = $booking;
        $this->isConfirmation = $isConfirmation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->isConfirmation 
            ? 'Booking Confirmed - Ticket: ' . $this->booking->ticket_number
            : 'Booking Receipt - Ticket: ' . $this->booking->ticket_number;

        return $this->subject($subject)
                    ->view('emails.customer_ticket');
    }
}
