<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): \Illuminate\Mail\Mailables\Envelope
    {
        $human = str_replace('_', ' ', $this->order->status);

        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Order '.$this->order->order_number.' is now '.$human,
        );
    }

    public function content(): \Illuminate\Mail\Mailables\Content
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.order-status',
            with: ['order' => $this->order],
        );
    }
}