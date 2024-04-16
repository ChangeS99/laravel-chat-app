<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    public $table = 'messages';
    protected $fillable = ['id', 'user_id', 'text', 'group_id', 'conversation_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTimeAttribute(): string
    {
        return date(
            'd M Y, H:i:s',
            strtotime($this->attributes('created_at'))
        );
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
