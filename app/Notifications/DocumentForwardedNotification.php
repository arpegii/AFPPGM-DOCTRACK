<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\DocumentForwardHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentForwardedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $forwardHistory;
    protected $isOriginalSender;

    public function __construct(Document $document, DocumentForwardHistory $forwardHistory, bool $isOriginalSender = false)
    {
        $this->document = $document;
        $this->forwardHistory = $forwardHistory;
        $this->isOriginalSender = $isOriginalSender;
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
        if ($this->isOriginalSender) {
            return (new MailMessage)
                ->subject('Your Document is Being Forwarded - ' . $this->document->document_number)
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('Your document is being forwarded to another unit.')
                ->line('**Document Number:** ' . $this->document->document_number)
                ->line('**Title:** ' . $this->document->title)
                ->line('**From Unit:** ' . $this->forwardHistory->fromUnit->name)
                ->line('**To Unit:** ' . $this->forwardHistory->toUnit->name)
                ->line('**Forwarded by:** ' . $this->forwardHistory->forwardedBy->name)
                ->when($this->forwardHistory->notes, function ($mail) {
                    return $mail->line('**Notes:** ' . $this->forwardHistory->notes);
                })
                ->action('Track Document', route('track.index'))
                ->line('Your document is moving through the system.');
        }

        return (new MailMessage)
            ->subject('Document Forwarded to Your Unit - ' . $this->document->document_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A document has been forwarded to your unit.')
            ->line('**Document Number:** ' . $this->document->document_number)
            ->line('**Title:** ' . $this->document->title)
            ->line('**From Unit:** ' . $this->forwardHistory->fromUnit->name)
            ->line('**To Unit:** ' . $this->forwardHistory->toUnit->name)
            ->line('**Forwarded by:** ' . $this->forwardHistory->forwardedBy->name)
            ->when($this->forwardHistory->notes, function ($mail) {
                return $mail->line('**Notes:** ' . $this->forwardHistory->notes);
            })
            ->action('View Document', route('documents.view', $this->document->id))
            ->line('Please review this document at your earliest convenience.');
    }

    /**
     * Get the array representation for database storage.
     */
    public function toArray($notifiable)
    {
        if ($this->isOriginalSender) {
            return [
                'document_id' => $this->document->id,
                'document_number' => $this->document->document_number,
                'title' => $this->document->title,
                'type' => 'document_forwarded',
                'message' => 'Your document is being forwarded from ' . $this->forwardHistory->fromUnit->name . ' to ' . $this->forwardHistory->toUnit->name,
                'from_unit' => $this->forwardHistory->fromUnit->name,
                'to_unit' => $this->forwardHistory->toUnit->name,
                'forwarded_by' => $this->forwardHistory->forwardedBy->name,
                'notes' => $this->forwardHistory->notes,
                'url' => route('track.index'),
                'is_original_sender' => true,
            ];
        }

        return [
            'document_id' => $this->document->id,
            'document_number' => $this->document->document_number,
            'title' => $this->document->title,
            'type' => 'document_forwarded',
            'message' => 'Document forwarded from ' . $this->forwardHistory->fromUnit->name . ' to ' . $this->forwardHistory->toUnit->name,
            'from_unit' => $this->forwardHistory->fromUnit->name,
            'to_unit' => $this->forwardHistory->toUnit->name,
            'forwarded_by' => $this->forwardHistory->forwardedBy->name,
            'notes' => $this->forwardHistory->notes,
            'url' => route('documents.view', $this->document->id),
            'is_original_sender' => false,
        ];
    }
}