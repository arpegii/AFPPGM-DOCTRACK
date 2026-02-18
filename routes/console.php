<?php

use App\Models\Document;
use App\Models\User;
use App\Notifications\DocumentOverdueNotification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('documents:notify-overdue', function () {
    $cutoff = now()->subDays(3);

    $overdueDocuments = Document::with(['senderUnit', 'receivingUnit'])
        ->where('status', 'incoming')
        ->where(function ($query) use ($cutoff) {
            $query->where(function ($subQuery) use ($cutoff) {
                $subQuery->whereNotNull('forwarded_at')
                    ->where('forwarded_at', '<=', $cutoff);
            })->orWhere(function ($subQuery) use ($cutoff) {
                $subQuery->whereNull('forwarded_at')
                    ->where('created_at', '<=', $cutoff);
            });
        })
        ->get();

    $sentCount = 0;

    foreach ($overdueDocuments as $document) {
        $unitUsers = User::where('unit_id', $document->receiving_unit_id)->get();

        foreach ($unitUsers as $user) {
            $alreadyEmailed = $user->notifications()
                ->where('type', DocumentOverdueNotification::class)
                ->where('data->document_id', $document->id)
                ->where('data->receiving_unit_id', $document->receiving_unit_id)
                ->whereNotNull('data->email_sent_at')
                ->exists();

            if ($alreadyEmailed) {
                continue;
            }

            $user->notify(new DocumentOverdueNotification($document));
            $sentCount++;
        }
    }

    $this->info("Overdue notifications sent: {$sentCount}");
})->purpose('Notify unit users of incoming documents overdue for 3+ days');

Schedule::command('documents:notify-overdue')->everyMinute();
