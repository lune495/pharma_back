<?php

namespace App\Exports;

use App\Http\Controllers\ProduitController;
// use App\Model\Bon;
// use App\Model\PaiementVente;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\{Produit,Outil};


//use App\Model\Vente;



class ProduitView implements FromView
{


    public function __construct()
    {

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        Outil::setParametersExecution();
        return view('pdf.produit', [
            'data'    => ProduitController::getDataForExport(),
            'is_excel'   => true,
        ]);
    }
}
