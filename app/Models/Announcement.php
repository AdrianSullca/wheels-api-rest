<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    protected $table = 'announcements';

    protected $fillable = [
        'user_id',
        'title',
        'price',
        'description',
        'kilometers',
        'brand',
        'model',
        'year',
        'state'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'announcement_id');
    }
}
