<?php

namespace App\Imports;

use App\Models\Produit;
//use Maatwebsite\Excel\Concerns\ToModel;

class ImportProduit implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Produit([
            //
           'code'        => $row[0],
           'designation' => $row[1],
           'image'       => $row[2],
           'pa'          => $row[3],
           'pv'          => $row[4],
           'limite'      => $row[5],
           'qte'         => $row[6],
        ]);
    }
}
