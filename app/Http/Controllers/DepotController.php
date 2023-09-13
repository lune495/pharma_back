<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depot;

class DepotController extends Controller
{
    //
    public function save(Request $request)
    {
        try 
        {
                $errors =null;
                $item = new Depot();
                if (!empty($request->id))
                {
                    $item = Depot::find($request->id);
                }
                if (empty($request->produit_id))
                {
                    $errors = "Renseignez le produit";
                }
                if (empty($request->stock))
                {
                    $errors = "Renseignez le stock";
                }
                if (empty($request->pa))
                {
                    $errors = "Renseignez le prix d'achat";
                }
                $item->produit_id = $request->produit_id;
                $item->stock = $request->stock;
                $item->pa = $request->pa;
                $item->limite = $request->limite;
                    if (!isset($errors)) 
                    {
                        $item->save();
                        return $item;
                    }
                    if (isset($errors))
                    {
                        throw new \Exception('{"data": null, "errors": "'. $errors .'" }');
                    }
        } catch (\Throwable $e) {
                return $e->getMessage();
        }
    }
}