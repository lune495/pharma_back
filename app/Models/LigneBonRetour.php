<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneBonRetour extends Model
{
    use HasFactory;

    protected $fillable = [
        'bon_retour_id',
        'produit_id',
        'quantite_retour',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function bon_retour()
    {
        return $this->belongsTo(BonRetour::class);
    }
}