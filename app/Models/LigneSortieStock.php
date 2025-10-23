<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneSortieStock extends Model
{
    use HasFactory;
    public  function produit()
    {
        return $this->belongsTo(Produit::class);
    }
    public  function sortie_stock()
    {
        return $this->belongsTo(SortieStock::class);
    }
}
