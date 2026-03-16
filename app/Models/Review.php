<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'paper_id',
        'reviewer_id',
        'comment',
        'recommendation',
    ];

    public function paper(){
        return $this->belongsTo(Paper::class);
    }
    public function reviewer(){
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
