<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Alerta extends Notification
{
    use Queueable;

    protected $titulo, $mensaje, $responsable, $clase;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($titulo, $mensaje, $responsable, $clase)
    {
        //
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->responsable = $responsable;
        $this->clase = $clase;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'titulo' => $this->titulo,
            'mensaje' => $this->mensaje,
            'responsable' => $this->responsable,
            'clase' => $this->clase
        ];
    }
}
