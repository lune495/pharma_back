<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaProduit extends Model
{
    use HasFactory;
    public  function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }
     public  function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
