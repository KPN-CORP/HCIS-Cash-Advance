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
    public $managerName;
    public $approvalLink;
    public $rejectionLink;

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
        $this->managerName = $data['managerName'];
        $this->approvalLink = $data['approvalLink'];
        $this->rejectionLink = $data['rejectionLink'];
    }

    public function build()
    {
<<<<<<< HEAD
        return $this->subject('New Business Trip Request')
            ->view('hcis.reimbursements.approval.email.htlNotification');
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hotel Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'hcis.reimbursements.approval.email.htlNotification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
=======
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

>>>>>>> origin/newBt
    public function attachments(): array
    {
        return [];
    }
}
