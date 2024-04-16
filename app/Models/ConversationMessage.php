<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{
    use HasFactory;

    protected $table = "conversations_messages";

    // properties that can be accessed or edited
    protected $fillable  = [
        'id',
        'message_id',
        'conversation_id',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
