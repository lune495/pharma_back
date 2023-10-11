<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\{Produit,Inventaire,LigneInventaire,User,Outil};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\NewSaleEvent;

use \PDF;

class InventaireController extends Controller
{
    private $queryName = "inventaires";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return Vente::all();

    }

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
                $item = new Inventaire();
                $log = new LigneInventaire();
                $user = Auth::user();
                // $user_id = auth('sanctum')->user()->id;
                $qte_total_inventaire = 0;
                if (!empty($request->id))
                {
                    $item = Inventaire::find($request->id);
                }
                    DB::beginTransaction();
                    // $item->user_id = 1;
                    $item->user_id = $user->id;
                    $str_json = json_encode($request->details);
                    $details = json_decode($str_json, true);
                        if (!isset($errors)) 
                        {
                            $item->save();
                            foreach ($details as $detail) 
                            {
                                $produit = Produit::find($detail['produit_id']);
                                if (!isset($produit)) {
                                $errors = "Produit inexistant";
                                }
                                else 
                                {
                                $quantite_theorique = $produit->qte; 
                                $ligne_inventaire = new LigneInventaire(); 
                                $ligne_inventaire->produit_id = $detail['produit_id'];
                                $ligne_inventaire->inventaire_id  = $item->id;
                                $ligne_inventaire->quantite_reel = $detail['quantite_reel'];
                                $ligne_inventaire->quantite_theorique = $quantite_theorique;
                                $ligne_inventaire->diff_inventaire = $detail['quantite_reel'] - $quantite_theorique;
                                $saved = $ligne_inventaire->save();
                                }
                                if($saved)
                                {
                                    if ($detail['quantite_reel'] >  $quantite_theorique) {
                                        $produit->qte = $produit->qte + ($detail['quantite_reel'] - $quantite_theorique);
                                        $produit->save();
                                    }
                                    if ($detail['quantite_reel'] <  $quantite_theorique) {
                                        $produit->qte = $produit->qte - (-1 * ($detail['quantite_reel'] - $quantite_theorique));
                                        $produit->save();
                                    }
                                }
                            }
                        }
                        if (!isset($errors)) 
                        {
                            $item->ref = "InV0{$item->id}";
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
