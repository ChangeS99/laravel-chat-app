<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Set up relation with groups as
     * user can be part of many groups
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'groups_users', 'users_id', 'groups_id');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends_users', 'user_id', 'friend_id');
    }

    public function isFriendsWith(User $user)
    {
        return $this->friends()->where('friend_id', $user->id)->exists();
    }

    public function hasConversation($conversation_id)
    {
        return DB::table('converstations')->where('conversation_id', $conversation_id)->where('member_1', $this->id)->orWhere('member_2', $this->id)->exists();
    }
}
