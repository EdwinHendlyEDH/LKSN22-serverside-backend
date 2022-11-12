<?php

namespace App\Models;

use App\Models\AllowedDomains;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "slug",
        "creator_id",
        "description",
        "limit_one_response"
    ];

    // protected $with = ['questions', 'creator'];

    public function questions(){
        return $this->hasMany(Question::class)->orderBy('created_at', 'desc');
    }

    public function creator(){
        return $this->belongsTo(User::class);
    }

    public function allowed_domains(){
        return $this->hasMany(AllowedDomains::class);
    }
}
