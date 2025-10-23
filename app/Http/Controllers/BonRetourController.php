<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Produit,User,Outil,BonRetour,LigneBonRetour,Depot};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BonRetourController extends Controller
{
    //
    private $queryName = "bon_retours";
    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request)
            {
                //dd($request->all());
                $errors = null;
                $item = new BonRetour();
                $user = Auth::user();
                DB::beginTransaction();
                $item->nom_client = $request->nom_client;
                $item->user_id = $user->id;
                $str_json = json_encode($request->details);
                $details = json_decode($str_json, true);
                if (!isset($errors)) 
                {
                    $item->save();
                    $itemId = $item->id; 
                    foreach ($details as $detail) 
                    {
                        $getProduit = Produit::find($detail['produit_id']);
                        if($getProduit == null )
                        {
                            $errors = "Produit  inexistant";
                        }
                        else if(!isset($detail['quantite']) || !is_numeric($detail['quantite']) || $detail['quantite'] < 1)
                        {
                            $errors = "Veuillez défnir la quantité";
                        }
                        else
                        {

                            $itemDetail = LigneBonRetour::where('bon_retour_id',$itemId)->where('produit_id', $detail['produit_id'])->first();
                            if ($itemDetail==null)
                            {
                                $itemDetail = new LigneBonRetour();
                                $itemDetail->bon_retour_id = $itemId;
                                $itemDetail->produit_id = $detail['produit_id'];
                                $itemDetail->pu = $detail['pu'];
                                $itemDetail->quantite_retour = $detail['quantite'];
                                $saved = $itemDetail->save();
                            }
                            
                        }


                        if (isset($detail['produit_id']))
                        {
                            $depot = Depot::where("produit_id",$detail['produit_id'])->where("initial_depot_id",1)->first();
                        }
                        if(!$depot)
                        {
                            $errors = "Ce produit n'existe pas au niveau de la pharmacie";
                        }
                        if (!isset($errors)) 
                        {
                            $depot->stock = $depot->stock + $detail['quantite'];
                            $depot->save();
                            $getProduit->stock_pharma = $getProduit->stock_pharma + $detail['quantite'];
                            $getProduit->save();
                        }
                        }   
                        $item->ref = "CHIF-BR00{$item->id}";
                        $item->save();
                    }
                if (isset($errors))
                {
                    throw new \Exception($errors);
                }
                DB::commit();
                //return $item;
                return  Outil::redirectgraphql($this->queryName, "id:{$itemId}", Outil::$queries[$this->queryName]);
            });
        } catch (exception $e) {            
             DB::rollback();
             return $e->getMessage();
        }
    }

    public function recompilationpulignbr()
    {
        try {
            return DB::transaction(function () 
            {
                $lignebonretours = LigneBonRetour::all();
                foreach ($lignebonretours as $lignbr) 
                {
                    $produit = Produit::find($lignbr->produit_id);
                    if($produit)
                    {
                        $lignbr->pu = $produit->pv;
                        $lignbr->save();
                    }
                }   
                return "Recompilation effectuée avec succès";
            });
        } catch (exception $e) {            
             DB::rollback();
             return $e->getMessage();
        }
    }
}
