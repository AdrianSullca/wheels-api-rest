<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'messages';
    
    protected $fillable = [
        'chat_id',
        'issuer_id',
        'content'
    ];
    
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issuer_id');
    }
}
