<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $receivedBy;

    public function __construct(Document $document, $receivedBy)
    {
        $this->document = $document;
        $this->receivedBy = $receivedBy;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Document Received by Receiver Unit - ' . $this->document->document_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your document has been marked as received.')
            ->line('**Document Number:** ' . $this->document->document_number)
            ->line('**Title:** ' . $this->document->title)
                        ->line('**From:** ' . ($this->document->senderUnit->name ?? 'Unknown Unit'))

            ->line('**Received by:** ' . $this->receivedBy->name)
            ->line('**Date:** ' . $this->document->received_at->format('F j, Y g:i A'))
            ->action('View Document', route('documents.view', $this->document->id))
            ->line('Thank you for using our document tracking system!');
    }

    /**
     * Get the array representation for database storage.
     */
    public function toArray($notifiable)
    {
        return [
            'document_id' => $this->document->id,
            'document_number' => $this->document->document_number,
            'title' => $this->document->title,
            'type' => 'document_received',
            'message' => 'Your document was received by ' . $this->receivedBy->name,
            'received_by' => $this->receivedBy->name,
            'received_at' => $this->document->received_at->format('F j, Y g:i A'),
            'url' => route('documents.view', $this->document->id),
        ];
    }
}