<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\DocumentForwardHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentMovingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $forwardHistory;

    public function __construct(Document $document, DocumentForwardHistory $forwardHistory)
    {
        $this->document = $document;
        $this->forwardHistory = $forwardHistory;
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
            ->subject('Document Update: Your Document is Moving - ' . $this->document->document_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your document is being processed and has been forwarded to another unit.')
            ->line('**Document Number:** ' . $this->document->document_number)
            ->line('**Title:** ' . $this->document->title)
            ->line('**Current Location:** ' . $this->forwardHistory->fromUnit->name)
            ->line('**Forwarded to:** ' . $this->forwardHistory->toUnit->name)
            ->line('**Forwarded by:** ' . $this->forwardHistory->forwardedBy->name)
            ->when($this->forwardHistory->notes, function ($mail) {
                return $mail->line('**Notes:** ' . $this->forwardHistory->notes);
            })
            ->action('Track Your Document', route('track.index'))
            ->line('Your document is moving through the workflow. You will be notified of any updates.');
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
            'type' => 'document_moving',
            'message' => 'Your document is being forwarded from ' . $this->forwardHistory->fromUnit->name . ' to ' . $this->forwardHistory->toUnit->name,
            'from_unit' => $this->forwardHistory->fromUnit->name,
            'to_unit' => $this->forwardHistory->toUnit->name,
            'forwarded_by' => $this->forwardHistory->forwardedBy->name,
            'notes' => $this->forwardHistory->notes,
            'url' => route('track.index'),
        ];
    }
}