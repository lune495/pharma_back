<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LigneApprovisionnements extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function approvisionnement()
    {
        return $this->belongsTo(Approvisionnement::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
                                                               
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}
