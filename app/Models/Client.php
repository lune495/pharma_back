<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }
    public function proformas()
    {
        return $this->hasMany(Proforma::class);
    }
}
