<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReservationFined extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sumPrice, $reservationsToBeFined)
    {
        $this->sumPrice = $sumPrice;
        $this->reservationsToBeFined = $reservationsToBeFined;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reservation-fined', [
            'sumPrice' => $this->sumPrice,
            'reservationsToBeFined' => $this->reservationsToBeFined
        ])->subject('Upomínka o nevrácení titulu');
    }
}
