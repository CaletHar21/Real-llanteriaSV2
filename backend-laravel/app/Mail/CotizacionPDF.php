<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class CotizacionPDF extends Mailable
{
    use Queueable, SerializesModels;

    public $cotizacion;

    /**
     * Create a new message instance.
     */
    public function __construct(Cotizacion $cotizacion)
    {
        $this->cotizacion = $cotizacion->load(['usuario', 'llanta']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Cotización de Llanta - Llantería SV',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cotizacion',
            with: [
                'cotizacion' => $this->cotizacion,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.cotizacion-pdf', [
            'cotizacion' => $this->cotizacion,
        ])->stream();

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $pdf,
                'cotizacion-' . $this->cotizacion->id . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
