<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SortieStock extends Model
{
    use HasFactory;
    public  function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public  function ligne_sortie_stocks()
    {
        return $this->hasMany(LigneSortieStock::class, 'sortie_stock_id', 'id');
    }
}
