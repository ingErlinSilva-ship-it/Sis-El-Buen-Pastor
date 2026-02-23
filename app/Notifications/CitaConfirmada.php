<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Cita;

class CitaConfirmada extends Notification
{
    use Queueable;

    protected $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $fecha = \Carbon\Carbon::parse($this->cita->fecha)->format('d/m/Y');
        $hora = \Carbon\Carbon::parse($this->cita->hora)->format('h:i A');

        return (new MailMessage)
            ->subject('Confirmación de Cita - Clínica El Buen Pastor')
            ->greeting('¡Hola ' . $notifiable->nombre . '!')
            ->line('Tu cita ha sido agendada correctamente.')
            ->line('📅 Fecha: ' . $fecha)
            ->line('🕒 Hora: ' . $hora)
            ->line('👨‍⚕️ Médico: ' . $this->cita->medico->usuario->nombre . ' ' . $this->cita->medico->usuario->apellido)
            ->line('📝 Motivo: ' . $this->cita->motivo)
            ->line('Por favor preséntate 5 minutos antes.')
            ->salutation('Atentamente, Clínica El Buen Pastor');
    }
}
