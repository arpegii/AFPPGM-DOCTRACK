<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentSentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Document Received - ' . $this->document->document_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new document has been sent to your unit.')
            ->line('**Document Number:** ' . $this->document->document_number)
            ->line('**Title:** ' . $this->document->title)
            ->line('**Type:** ' . $this->document->document_type)
            ->line('**From:** ' . ($this->document->senderUnit->name ?? 'Unknown Unit'))
            ->line('**Sent by:** ' . ($this->document->creator->name ?? 'System'))
            ->action('View Document', route('documents.view', $this->document->id))
            ->line('Please review this document at your earliest convenience.');
    }

    /**
     * Get the array representation for database storage.
     */
    public function toArray($notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'document_number' => $this->document->document_number,
            'title' => $this->document->title,
            'type' => 'document_sent',
            'message' => 'New document received from ' . ($this->document->senderUnit->name ?? 'Unknown Unit'),
            'sender_unit' => $this->document->senderUnit->name ?? 'Unknown Unit',
            'sender_name' => $this->document->creator->name ?? 'System',
            'url' => route('documents.view', $this->document->id),
        ];
    }
}