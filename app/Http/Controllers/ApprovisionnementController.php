<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Fournisseur,Produit,Approvisionnement,User,Depot,Outil,LigneApprovisionnement,InitialDepot};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use \PDF;


class ApprovisionnementController extends Controller
{
    //
    private $queryName = "approvisionnements";
    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request)
            {
                //dd($request->all());
                $errors = null;
                $item = new Approvisionnement();
                $user = Auth::user();
                $montant_total_appro = 0;
                $qte_total_appro = 0;
                if (isset($request->fournisseur_id))
                {
                    $fournisseur = Fournisseur::find($request->fournisseur_id);
                }
                // if(!isset($fournisseur))
                // {
                //     $errors = "Ce fournisseur n'existe pas";
                // }
                DB::beginTransaction();
                $initial_depot = InitialDepot::find($request->initial_depot_id);
                if (!isset($initial_depot)) 
                {
                    $errors = "Depot inexistant";
                }
                $str_json = json_encode($request->details);
                $details = json_decode($str_json, true);
                if (!isset($errors)) 
                {
                    $item->numero = $request->numero;
                    $item->date_facture_achat = $request->date_facture_achat;
                    $item->fournisseur_id = $request->fournisseur_id;
                    $item->initial_depot_id = $initial_depot->id;
                    $item->user_id = $user->id;
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
                            $itemDetail = LigneApprovisionnement::where('approvisionnement_id',$itemId)->where('produit_id', $detail['produit_id'])->first();
                            if ($itemDetail==null)
                            {
                                $itemDetail = new LigneApprovisionnement();
                                $itemDetail->approvisionnement_id = $itemId;
                                $itemDetail->produit_id = $detail['produit_id'];
                                $itemDetail->pu = $detail['pu'];
                            }
                            $itemDetail->quantity_received = $detail['quantite'];
                            $itemDetail->initial_depot_id = $initial_depot->id;
                            $saved = $itemDetail->save();
                            if($saved)
                            {
                                $qte_total_appro = $qte_total_appro + $itemDetail->quantity_received;
                                $montant_total_appro = $montant_total_appro  + ($detail['pu'] * $itemDetail->quantity_received);
                                if($initial_depot->nom_depot == 'MAGASIN')
                                {
                                    if (isset($detail['produit_id']))
                                    {
                                        $depot = Depot::where("produit_id",$detail['produit_id'])->where("initial_depot_id",2)->first();
                                    }
                                    if(!$depot)
                                    {
                                        $errors = "Ce produit n'existe pas dans le Magazin";
                                    }
                                    if (!isset($errors)) 
                                    {
                                        $depot->stock = $depot->stock + $detail['quantite'];
                                        $depot->save();
                                        $getProduit->stock_magasin = $getProduit->stock_magasin + $detail['quantite'];
                                        $getProduit->save();
                                    }
                                }
                                if($initial_depot->nom_depot == 'PHARMACIE')
                                {
                                    if (isset($detail['produit_id']))
                                    {
                                        $depot = Depot::where("produit_id",$detail['produit_id'])->where("initial_depot_id",2)->first();
                                    }
                                    if(!$depot)
                                    {
                                        $errors = "Ce produit n'existe pas dans le Magazin";
                                    }
                                    if (!isset($errors)) 
                                    {
                                        $depot->stock = $depot->stock + $detail['quantite'];
                                        $depot->save();
                                        $getProduit->stock_pharma = $getProduit->stock_pharma + $detail['quantite'];
                                        $getProduit->save();
                                    }
                                }
                            }
                        }
                    }
                    if($request->type_appro == 'DEPOT')
                    {
                        $item->type_appro = "DEPOT";
                    }
                    if($request->type_appro == 'BOUTIQUE')
                    {
                        $item->type_appro = "BOUTIQUE";
                    }
                    // $item->numero = "SN0002023FA0{$item->id}";
                    $item->montant = $montant_total_appro;
                    $item->qte_total_appro = $qte_total_appro;
                    $item->save();
                }
                if (isset($errors))
                {
                    throw new \Exception($errors);
                }
                DB::commit();
                return  Outil::redirectgraphql($this->queryName, "id:{$itemId}", Outil::$queries[$this->queryName]);
            });
        } catch (exception $e) {            
             DB::rollback();
             return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function abortappro($id)
    {
        //
        try 
        {
            $appro = Approvisionnement::find($id);
            if($appro && $appro->type_appro == 'BOUTIQUE')
            {
                if($appro->statut == 0)
                {
                    DB::beginTransaction();
                    $ligne_appros = LigneApprovisionnement::where('approvisionnement_id',$appro->id)->get();
                    foreach ($ligne_appros as $ligne_appro) 
                    {
                         if ($user->role->nom == 'MAGASINIER') {
                            $produit = Produit::where('id', $detail['produit_id'])->where('initial_depot_id', 2)->first();
                            $produit->stock_magasin = $produit->stock_magasin - $ligne_appro->quantity_received;
                            $produit->save();
                        }elseif ($user->role->nom == 'PHARMACIEN') {
                            $produit = Produit::where('id', $detail['produit_id'])->where('initial_depot_id', 1)->first();
                            $produit->stock_pharma = $produit->stock_pharma - $ligne_appro->quantity_received;
                            $produit->save();
                        }
                    }
                    $appro->statut = 1;
                    if($appro->save())
                    {
                        DB::commit();
                        $id = $appro->id;
                        return  Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
                    }
                }else{
                    return  Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
                } 
            }
            if($appro && $appro->type_appro == 'DEPOT')
            {
                if($appro->statut == 0)
                {
                    DB::beginTransaction();
                    $ligne_appros = LigneApprovisionnement::where('approvisionnement_id',$appro->id)->get();
                    foreach ($ligne_appros as $ligne_appro) 
                    {
                        $depot = Depot::where('produit_id',$ligne_appro->produit_id);
                        if($depot)
                        {
                            $depot->stock = $depot->stock - $ligne_appro->quantity_received;
                            $depot->save();
                        }
                    }
                    $appro->statut = 1;
                    if($appro->save())
                    {
                        DB::commit();
                        $id = $appro->id;
                        return  Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
                    }
                }else{
                    return  Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
                } 
            }
        } catch (exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
    public function genereallPDf($id)
    {
        // $pdf = PDF::loadView('pdf.Approvisionnement', [
        //     'items'  => self::getDataForExport(),
        //         ]);
        // $measure = array(0,0,1200,700);
        // return $pdf->setPaper($measure, 'landscape')->stream();

        // $data = Outil::getOneItemWithGraphQl($this->queryName, $id, true);
        // dd($data);
        // $pdf = PDF::loadView("pdf.ventesold", $data);
        // $measure = array(0,0,225.772,650.197);
        // return $pdf->setPaper($measure, 'orientation')->stream();

        $appro = Approvisionnement::find($id);
        if($appro!=null)
        {
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, true);
        //dd($data);
         $pdf = PDF::loadView("pdf.approvisionnements", $data);
        return $pdf->stream();
        }
        else
        {
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, false);
            return view('notfound');
        }
    }
}