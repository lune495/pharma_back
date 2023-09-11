<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Depot,Produit};
use Illuminate\Support\Facades\DB;

class MouvementController extends Controller
{
    //
    public function approdepot(Request $request)
    {
        try{
            return DB::transaction(function () use ($request)
            {
                $errors = null;
                if (isset($request->produit_id))
                {
                    $item = Depot::where("produit_id",$request->produit_id)->get();
                }
                if(!$item->first())
                {
                    $errors = "Ce produit n'existe pas";
                }
                if (empty($request->quantite))
                {
                    $errors = "Renseignez la quantite";
                }
                    if (!isset($errors)) 
                    {
                        $depot = Depot::find($item[0]['id']);
                        $depot->stock = $depot->stock + $request->quantite;
                        $depot->save();
                        return $depot;
                    }
                    if (isset($errors))
                    {
                        throw new \Exception('{"data": null, "errors": "'. $errors .'" }');
                    }
            });
        }catch (\Throwable $e) {
                return $e->getMessage();
        }
    }

    public function ravitaillerboutique(Request $request)
    {
        try{
            return DB::transaction(function () use ($request)
            {
                $errors = null;
                $depot = Depot::where("produit_id",$request->produit_id)->get();
                if (isset($request->produit_id))
                {
                    $item = Produit::where("id",$request->produit_id)->get();
                }
                if(!$item->first())
                {
                    $errors = "Ce produit n'existe pas";
                }
                if (empty($request->quantite))
                {
                    $errors = "Renseignez la quantite";
                }
                if($depot->first())
                {
                    if($depot[0]['stock'] < $request->quantite)
                    {
                        $errors = "La quantite a approvisionner n'existe pas en stock";
                    }
                }
                    
                    if (!isset($errors)) 
                    {
                        $produit = Produit::find($item[0]['id']);
                        $produit->qte = $produit->qte + $request->quantite;
                        $produit->save();
                        $depots = Depot::find($depot[0]['id']);
                        $stock = $depots->stock - $request->quantite;
                        if(DB::table('depots')->where('produit_id',$request->produit_id)->exists() == true)
                        {
                          DB::table('depots')->where('id', $depots->id)->update(['stock' => $stock]);
                        }
                        return $produit;
                    }
                    if (isset($errors))
                    {
                        throw new \Exception('{"data": null, "errors": "'. $errors .'" }');
                    }
            });
        }catch (\Throwable $e) {
                return $e->getMessage();
        }
    }
}
