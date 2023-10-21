<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Produit,User,Outil,BonRetour,LigneBonRetour};
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
                            }
                            $itemDetail->quantite_retour = $detail['quantite'];
                            $saved = $itemDetail->save();
                        }
                        if (isset($detail['produit_id']))
                        {
                            $produit = Produit::where("id",$detail['produit_id'])->get();
                        }
                        if(!$item->first())
                        {
                            $errors = "Ce produit n'existe pas";
                        }
                        if (!isset($errors)) 
                        {
                            $produit = Produit::find($produit[0]['id']);
                            $produit->qte = $produit->qte + $detail['quantite'];
                            $produit->save();
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
}
