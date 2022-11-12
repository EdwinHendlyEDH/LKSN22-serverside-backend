<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['question'];

    public function response(){
        return $this->belongsTo(Response::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }
}
