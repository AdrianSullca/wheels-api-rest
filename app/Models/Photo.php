<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $table = 'photos';

    protected $fillable = [
        'announcement_id',
        'image_url',
        'image_public_id'
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}
