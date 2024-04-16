<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $table = "conversations";

    protected $fillable = [
        'id',
        'member_1',
        'member_2'
    ];

    public function member_1()
    {
        return $this->belongsTo(User::class, 'member_1');
    }

    public function member_2()
    {
        return $this->belongsTo(User::class, 'member_2');
    }

    // many to many relation with mesasges with conversations_messages table

    public function messages()
    {
        return $this->belongsToMany(Message::class, 'conversations_messages', 'conversation_id', 'message_id');
    }
}
