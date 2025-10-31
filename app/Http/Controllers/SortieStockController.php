<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Produit,SortieStock,LigneSortieStock,User,Outil,Module};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SortieStockController extends Controller
{
    //
    private $queryName = "sortiestocks";

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new SortieStock();
            $log = new LigneSortieStock();
            $user = Auth::user();
            // $user_id = auth('sanctum')->user()->id;
            $qte_total_inventaire = 0;
            if (!empty($request->id))
            {
                $item = SortieStock::find($request->id);
            }
            
            DB::beginTransaction();
            $module = Module::find($request->module_id);
            if (!isset($module)) {
                $errors = "Module inexistant";
            }
            // $item->user_id = 1;
            $item->user_id = $user->id;
            $item->module_id = $request->module_id;
            $str_json = json_encode($request->details);
            $details = json_decode($str_json, true);
            if (!isset($errors)) 
            {
                $item->save();
                //dd($request->all());
                // if ($item->user->role_id == 3) 
                // {
                    foreach ($details as $detail) 
                    {
                        $produit = Produit::find($detail['produit_id']);
                        if (!isset($produit)) 
                        {
                            $errors = "Produit inexistant";
                        }
                        else 
                        {
                            $quantite_stock = $produit->qte; 
                            $ligne_sortie_stock = new LigneSortieStock(); 
                            $ligne_sortie_stock->produit_id = $detail['produit_id'];
                            $ligne_sortie_stock->sortie_stock_id  = $item->id;
                            $ligne_sortie_stock->quantite = $detail['quantite'];
                            $ligne_sortie_stock->quantite_stock = $quantite_stock;
                            $saved = $ligne_sortie_stock->save();
                        }
                        if($saved)
                        {
                            // Mouvement de Stock en fonction du Depot
                            /*
                                // PHARMACIE
                                if ($item->user->role_id == 3) 
                                {
                                    $produit_depot_pharma = Depot::where('produit_id', $detail['produit_id'])->where('initial_depot_id', '=', 1)->first();
                                    if($produit_depot_pharma)
                                    {
                                        $produit_depot_pharma->stock = isset($produit_depot_pharma) ? $produit_depot_pharma->stock - $detail['quantite'] : $produit_depot_pharma->stock;
                                        $produit_depot_pharma->save();
                                    }
                                }
                                // MAGASIN
                                if ($item->user->role_id == 18) 
                                {
                                    $produit_depot = Depot::where('produit_id', $detail['produit_id'])->where('initial_depot_id', '=', 2)->first();
                                    if($produit_depot)
                                    {
                                        $produit_depot->stock = isset($produit_depot) ? $produit_depot->stock - $detail['quantite'] : $produit_depot->stock;
                                        $produit_depot->save();
                                    }
                                }
                            */
                            $produit->qte = $produit->qte - $detail['quantite'];
                            $produit->save();
                        }
                    }
            }
            if (!isset($errors)) 
            {
                $item->ref = "Sort0{$item->id}";
                $item->save();
                DB::commit();
                return  Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]);
            }
            if (isset($errors))
            {
                throw new \Exception($errors);
            }
        } catch (exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}