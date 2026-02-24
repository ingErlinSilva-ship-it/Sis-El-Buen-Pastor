<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoUsuarioAcceso extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $usuario;
    private $password;

    // El constructor DEBE recibir las dos cosas
    public function __construct($usuario, $password)
    {
        $this->usuario = $usuario;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('🔐 Datos de Acceso - Clínica El Buen Pastor')
            ->greeting('¡Hola, ' . $notifiable->nombre . '!')
            ->line('Tu cuenta ha sido creada exitosamente a través de nuestro Asistente Virtual.')
            ->line('Tus credenciales para ingresar al sistema son:')
            ->line('📧 **Usuario:** ' . $notifiable->email)
            ->line('🔑 **Contraseña:** ' . $this->password) // Asegúrate de recibir $password en el constructor
            ->action('Ingresar al Sistema', url('/login'))
            ->line('Te recomendamos cambiar tu contraseña al ingresar.')
            ->salutation('Saludos, el equipo de Clínica El Buen Pastor.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
