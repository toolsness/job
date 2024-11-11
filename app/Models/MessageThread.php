<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThread extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'inquiry_type'];

    public function messages()
    {
        return $this->hasMany(Message::class, 'thread_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'thread_id')->latest('sent_at');
    }
}
