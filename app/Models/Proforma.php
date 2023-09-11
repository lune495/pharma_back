<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public  function client()
    {
        return $this->belongsTo(Client::class);
    }
    public  function proforma_produits()
    {
        return $this->hasMany(ProformaProduit::class, 'proforma_id', 'id');
    }
     public function taxe()
    {
        return $this->belongsTo(Taxe::class);
    }
     public function remise()
    {
        return $this->belongsTo(Remise::class);
    }
}
