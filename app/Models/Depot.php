<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;
    protected $fillable = [
        'produit_id',
        'stock',
        'pa',
        'limite',
        'fournisseur_id',
        'initial_depot_id',
    ];
    public  function produit()
    {
        return $this->belongsTo(Produit::class,'produit_id');
    }
}