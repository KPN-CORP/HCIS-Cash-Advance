<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HotelNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $noSppd;
    public $namaHtl;
    public $lokasiHtl;
    public $tglMasukHtl;
    public $tglKeluarHtl;
    public $totalHari;
    public $noHtlList;
    public $approvalStatus;

    public function __construct(array $data)
    {
        $this->noHtlList = $data['noHtl'];
        $this->noSppd = $data['noSppd'];
        $this->namaHtl = $data['namaHtl'];
        $this->lokasiHtl = $data['lokasiHtl'];
        $this->tglMasukHtl = $data['tglMasukHtl'];
        $this->tglKeluarHtl = $data['tglKeluarHtl'];
        $this->totalHari = $data['totalHari'];
        $this->approvalStatus = $data['approvalStatus'];
    }

    public function build()
    {
        return $this->subject('New Hotel Request')
            ->view('hcis.reimbursements.businessTrip.email.htlNotification');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hotel Request Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'hcis.reimbursements.businessTrip.email.htlNotification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
