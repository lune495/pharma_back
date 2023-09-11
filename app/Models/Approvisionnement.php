<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approvisionnement extends Model
{
    use HasFactory;

    public  function user()
    {
        return $this->belongsTo(User::class);
    }

    public  function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function ligne_approvisionnements()
    {
        return $this->hasMany(LigneApprovisionnement::class, 'approvisionnement_id', 'id')->orderBy('id');
    }
    // public static function getAllqte()
    // {
    //     $quantity = 0;
    //     $all_ligne_appros = LigneApprovisionnement::all();

    //     foreach ($all_ligne_appros as $one_ligne)
    //     {
    //         $quantity += $one_ligne->quantity_received;
    //     }

    //     return $quantity;
    // }
}
