<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneBonRetour extends Model
{
    use HasFactory;

    public  function produit()
    {
        return $this->belongsTo(Produit::class);
    }
    public  function bon_retour()
    {
        return $this->belongsTo(BonRetour::class);
    }
}