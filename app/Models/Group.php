<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'name',
        'owner_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'groups_users', 'groups_id', 'users_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
