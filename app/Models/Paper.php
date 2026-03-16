<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Logging\OpenTestReporting\Status;

class Paper extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'abstract',
        'file',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    } 
    
     public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
