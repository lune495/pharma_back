<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneInventaire extends Model
{
    use HasFactory;
    
    public  function produit()
    {
        return $this->belongsTo(Produit::class);
    }
    public  function inventaire()
    {
        return $this->belongsTo(Inventaire::class);
    }
}
