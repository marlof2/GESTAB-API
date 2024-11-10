<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordNotification extends Notification
{
    use Queueable;

    private string $name;
    private string $token;

    public function __construct(string $token,string $name)
    {
        $this->name = $name;
        $this->token = $token;
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Recuperação de Senha')
            ->greeting("Olá {$this->name}!")
            ->line('Você está recebendo este e-mail porque recebemos uma solicitação de recuperação de senha para sua conta.')
            ->line('Seu token de recuperação é:')
            ->line($this->token)
            ->line('Copie o token e cole no campo de recuperação de senha no aplicativo.')
            ->line('Se você não solicitou uma recuperação de senha, nenhuma ação é necessária.')
            ->salutation('Atenciosamente, suporte da Gestab');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toArray($notifiable)
    {
        return [
            // Add any necessary data here
        ];
    }
}
