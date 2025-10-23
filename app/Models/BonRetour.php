<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonRetour extends Model
{
    use HasFactory;

    public function ligne_bon_retours()
    {    
        return $this->hasMany(LigneBonRetour::class);
    }
    public  function user()
    {
        return $this->belongsTo(User::class);
    }
}