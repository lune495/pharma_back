<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\{Produit,Inventaire,LigneInventaire,User,Outil,Module,InitialDepot};
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
            // dd($request->all());
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
                    $str_json = json_encode($request->details);
                    $details = json_decode($str_json, true);
                    $initial_depot = InitialDepot::find($request->initial_depot_id);
                    if (!isset($initial_depot)) 
                        {
                            $errors = "Depot inexistant";
                        }
                        if (!isset($errors)) 
                        {
                            $item->user_id = $user->id;
                            $item->initial_depot_id = $initial_depot->id;
                            $item->save();
                            foreach ($details as $detail) 
                            {
                                $produit = Produit::find($detail['produit_id']);
                                if (!isset($produit)) {
                                $errors = "Produit inexistant";
                                }
                                else 
                                {
                                if ($initial_depot->nom_depot == 'MAGASIN') {  
                                     $quantite_theorique = $produit->stock_magasin; 
                                }if ($initial_depot->nom_depot == 'PHARMACIE') {
                                    $quantite_theorique = $produit->stock_pharma;
                                }
                                $ligne_inventaire = new LigneInventaire(); 
                                $ligne_inventaire->produit_id = $detail['produit_id'];
                                $ligne_inventaire->inventaire_id  = $item->id;
                                $ligne_inventaire->quantite_reel = $detail['quantite_reel'];
                                $ligne_inventaire->quantite_theorique = $quantite_theorique;
                                $ligne_inventaire->diff_inventaire = $detail['quantite_reel'] - $quantite_theorique;
                                $ligne_inventaire->initial_depot_id = $request->initial_depot_id;
                                $saved = $ligne_inventaire->save();
                                }
                                if($saved)
                                {
                                    if ($initial_depot->nom_depot == 'MAGASIN') {
                                          if (($detail['quantite_reel'] >  $quantite_theorique) ) {
                                            $produit->stock_magasin = $produit->stock_magasin + ($detail['quantite_reel'] - $quantite_theorique);
                                            $produit->stock_initial_magasin = $produit->stock_initial_magasin == 0 
                                                                ?
                                                             $produit->stock_initial_magasin + ($detail['quantite_reel'] - $quantite_theorique)
                                                             : 0;
                                            $produit->save();
                                        }
                                        if (($detail['quantite_reel'] <  $quantite_theorique)) {
                                            $produit->stock_magasin = $produit->stock_magasin - (-1 * ($detail['quantite_reel'] - $quantite_theorique));
                                            $produit->stock_initial_magasin = $produit->stock_initial_magasin == 0 
                                                                ?
                                                             $produit->stock_initial_magasin - (-1 * ($detail['quantite_reel'] - $quantite_theorique))
                                                             : 0;
                                            $produit->save();
                                        }
                                    }

                                    if ($initial_depot->nom_depot == 'PHARMACIE') {
                                          if (($detail['quantite_reel'] >  $quantite_theorique) ) {
                                             $produit->stock_pharma = $produit->stock_pharma + ($detail['quantite_reel'] - $quantite_theorique);
                                             $produit->stock_initial_pharma = $produit->stock_initial_pharma == 0 
                                                                ?
                                                             $produit->stock_initial_pharma + ($detail['quantite_reel'] - $quantite_theorique)
                                                             : 0;
                                            $produit->save();
                                        }
                                        if (($detail['quantite_reel'] <  $quantite_theorique)) {
                                            $produit->stock_pharma = $produit->stock_pharma - (-1 * ($detail['quantite_reel'] - $quantite_theorique));
                                            $produit->stock_initial_pharma = $produit->stock_initial_pharma == 0 
                                                                ?
                                                             $produit->stock_initial_pharma - (-1 * ($detail['quantite_reel'] - $quantite_theorique))
                                                             : 0;
                                            $produit->save();
                                        }
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