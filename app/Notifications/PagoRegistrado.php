<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PagoRegistrado extends Notification
{
    use Queueable;

    protected $payment;

    /**
     * Crea una nueva instancia.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Canales de entrega: base de datos y correo.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Representación de la notificación vía correo.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Nuevo Pago Registrado')
            ->greeting('Hola ' . $notifiable->name . ' 👋')
            ->line("Se ha registrado un nuevo pago de S/. {$this->payment->total_amount}.")
            ->line("Huésped: {$this->payment->guest->name} {$this->payment->guest->last_name}")
            ->line("Fecha de pago: {$this->payment->payment_date->format('d/m/Y')}")
            ->action('Ver pagos', route('admin.payment.index'))
            ->line('Gracias por usar nuestro sistema de gestión hotelera 🏨');
    }

    /**
     * Representación de la notificación en base de datos.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'payment_id'     => $this->payment->id,
            'guest_name'     => $this->payment->guest->name . ' ' . $this->payment->guest->last_name,
            'total_amount'   => $this->payment->total_amount,
            'payment_date'   => $this->payment->payment_date->format('d/m/Y'),
            'payment_method' => $this->payment->payment_method,
            'message'        => "Nuevo pago de S/. {$this->payment->total_amount} registrado por {$this->payment->guest->name}.",
            'url'            => route('admin.payment.index'),
        ];
    }

    /**
     * Representación en array (opcional).
     */
    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
