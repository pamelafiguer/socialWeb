<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Bus\Queueable;

class FriendRequestNotification extends Notification
{
    use Queueable;

    private $sender;

    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];  
    }

    public function toDatabase($notifiable)
    {
        return [
            'sender_name' => $this->sender->name,
            'message' => $this->sender->name . ' te ha enviado una solicitud de amistad.'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new DatabaseMessage([
            'sender_name' => $this->sender->name,
            'message' => $this->sender->name . ' te ha enviado una solicitud de amistad.'
        ]);
    }
    public function toArray($notifiable)
{
    return [
        'message' => "{$this->sender->name} te ha enviado una solicitud de amistad",
    ];
}
}