<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneApprovisionnement extends Model
{
    use HasFactory;

    public function approvisionnement()
    {
        return $this->belongsTo(Approvisionnement::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
