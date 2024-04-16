<?php

namespace App\Jobs;

use App\Events\GotMessage;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
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
        GotMessage::dispatch([
            "message" => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'text' => $message->text,
                'group_id' => $message->group_id,
                'conversation_id' => $message->conversation_id
            ],
            "deleted" => $deleted
            // 'time' => $this->message->time,
        ]);
    }
}
