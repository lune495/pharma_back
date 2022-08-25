<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\{Produit,VenteProduit,Vente,User,Outil};
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
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
                $item = new Vente();
                $user_id = auth('sanctum')->user()->id;
                $montant_total_vente = 0;
                $qte_total_vente = 0;
                if (!empty($request->id))
                {
                    $item = Vente::find($request->id);
                }
                if (empty($request->montantencaisse))
                {
                    $errors = "Renseignez le montant encaisse";
                }
                    DB::beginTransaction();
                    $item->montantencaisse = $request->montantencaisse;
                    $item->monnaie = $request->monnaie;
                    $item->user_id = $user_id;
                    $str_json = json_encode($request->details);
                    $details = json_decode($str_json, true);
                    try
                    {
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
                                    $current_quantity = $produit->qte;
                                    if ($current_quantity < $detail['quantite']) 
                                    {
                                        $errors = "<strong class='text-capitalize'>{$produit->designation}</strong> a un stock de <strong class='text-capitalize'>{$current_quantity}</strong><br> Vous ne pouvez pas effectuer cette vente";
                                        break;
                                    }
                                    else 
                                    {
                                        $venteprdt = new VenteProduit(); 
                                        $venteprdt->produit_id = $detail['produit_id'];
                                        $venteprdt->vente_id = $item->id;
                                        $venteprdt->qte = $detail['quantite'];
                                        $saved = $venteprdt->save();
                                        if($saved)
                                        {
                                            $produit->qte = $produit->qte - $venteprdt->qte;
                                            $qte_total_vente = $qte_total_vente + $venteprdt->qte;
                                            $montant_total_vente = $montant_total_vente  + ($produit->pv * $venteprdt->qte);
                                            $produit->save();
                                        }
                                    }
                                }
                            }
                            $item->montant = $montant_total_vente;
                            $item->qte = $qte_total_vente;
                            $item->save();
                        }
                    }
                    catch (\Exception $e)
                    {
                        throw new \Exception('{"data": null, "errors": "'.$e->getMessage().'" }');
                    }
                    DB::commit();
                    throw new \Exception($errors);
        } catch (\Throwable $e) {
                return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Vente::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $vente = Vente::find($id);
        $vente->update($request->all());
        return $vente;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        return Produit::destroy($id);
    }

     /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        //
        return Produit::where('code','like','%'.$name.'%')->get();
    }
}
