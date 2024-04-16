<?php

namespace App\Jobs;

use App\Events\GotConversationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendConversationMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $messageEvent)
    {
        //

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $message = $this->messageEvent["message"];
        $deleted = $this->messageEvent["deleted"];

        //
        GotConversationMessage::dispatch([
            "message" => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'text' => $message->text,
                'conversation_id' => $message->conversation_id
            ],
            "deleted" => $deleted
            // 'time' => $this->message->time,
        ]);
    }
}
