<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialDepot extends Model
{
    use HasFactory;

    public  function depots()
    {
        return $this->hasMany(Depot::class, 'initial_depot_id', 'id');
    }
}
