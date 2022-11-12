<?php

namespace App\Models;

use App\Models\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllowedDomains extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function form(){
        return $this->belongsTo(Form::class);
    }

}
