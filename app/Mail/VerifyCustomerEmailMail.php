<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyCustomerEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Customer $customer, public readonly string $verificationUrl)
    {
    }

    public function build(): self
    {
        return $this->subject('Verify your MedMart account')
            ->view('emails.verify-customer-email');
    }
}
