<?php

namespace App\Models;

use App\Models\Form;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Response extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // protected $with = ['answers']; memory too big :(

    public function form(){
        return $this->belongsTo(Form::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }
}
