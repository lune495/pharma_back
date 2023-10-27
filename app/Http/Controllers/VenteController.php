<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\{Produit,VenteProduit,Vente,User,Outil,Taxe,Remise,Log,BonRetour};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\MyEvent;

use \PDF;

class VenteController extends Controller
{
    private $queryName = "ventes";
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return Vente::all();

    }

    public function Notif()
    {
        $data = Outil::getOneItemWithGraphQl($this->queryName,9, true);
        event(new MyEvent($data));
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
                $log = new Log();
                $user = Auth::user();
                // $user_id = auth('sanctum')->user()->id;
                $montant_total_vente = 0;
                $qte_total_vente = 0;
                if (!empty($request->id))
                {
                    $item = Vente::find($request->id);
                }
                
                DB::beginTransaction();
                // $item->montantencaisse = $request->montantencaisse;
                // $item->monnaie = $request->monnaie;
                // $item->client_id = $request->client_id;
                $item->user_id = $user->id;
                // $item->user_id = 2;
                $item->nom_client = $request->nom_complet;
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
                            else if(!isset($detail['quantite']) || !is_numeric($detail['quantite']) || $detail['quantite'] < 1)
                            {
                                $errors = "Veuillez défnir la quantité";
                                break;
                            }
                            if (empty($detail['prix_vente']))
                            {
                                $errors = "Renseignez le prix unitaire du produit : {$produit->designation}";
                            }
                            else 
                            {
                                $current_quantity = $produit->qte; 
                                if ($current_quantity < $detail['quantite']) 
                                {
                                    $errors = "{$produit->designation} a un stock de {$current_quantity} Vous ne pouvez pas effectuer cette vente";
                                    break;
                                }
                                else 
                                {
                                    $venteprdt = new VenteProduit(); 
                                    $venteprdt->produit_id = $detail['produit_id'];
                                    $venteprdt->vente_id  = $item->id;
                                    $venteprdt->qte = $detail['quantite'];
                                    $venteprdt->prix_vente = $detail['prix_vente'];
                                    $venteprdt->remise = $detail['remise'] > 0 ? $detail['remise'] : 0;
                                    $saved = $venteprdt->save();
                                    if($saved)
                                    {
                                        // $produit->qte = $produit->qte != null ? $produit->qte - $venteprdt->qte : $venteprdt->qte;
                                        $qte_total_vente = $qte_total_vente + $venteprdt->qte;
                                        $montant_total_vente = $montant_total_vente  + ($detail['prix_vente'] * $venteprdt->qte);
                                        $produit->save();
                                    }
                                }
                            }
                        }
                    }
                    if (!isset($errors)) 
                    { 
                        $tva = !(array_key_exists('tva', $request->all())) ? false : Taxe::first();
                        if($tva!= false && $tva->value != null){
                            $item->taxe_id = $tva->id;
                        }
                        $item->montant = $montant_total_vente;
                        $item->qte = $qte_total_vente;
                        $item->numero = "FARMA0{$item->id}";
                        $item->save();
                        $id = $item->id;
                        $log->designation = "pharmacie";
                        $log->id_evnt = $id;
                        $log->date = $item->created_at;
                        $log->prix = $montant_total_vente;
                        $log->remise = 0;
                        $log->montant = 0;
                        $log->user_id = $user->id;
                        $log->statut_pharma = false;
                        // $log->user_id = 2;
                        $log->save();
                        DB::commit();
                        $data = Outil::getOneItemWithGraphQl($this->queryName,$id, true);
                        event(new MyEvent($data));
                        return  Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function abortsale($id)
    {
        //
        try 
        {
            $vente = Vente::find($id);
            if($vente){
                $log = Log::where('id_evnt',$vente->id)->first();
                // dd($log);
                if($vente->statut == 0)
                {
                    DB::beginTransaction();
                    $vnteprdts = VenteProduit::where('vente_id',$vente->id)->get();
                    foreach ($vnteprdts as $vnteprdt) 
                    {
                        $produit = Produit::find($vnteprdt->produit_id);
                        if($produit && $vente->paye == true)
                        {
                            $produit->qte = $produit->qte + $vnteprdt->qte;
                            $produit->save();
                        }
                    }
                    $vente->statut = 1;
                    if($vente->save())
                    {
                        if ($log) {
                            $log->statut_pharma = true;
                            $log->save();
                        }
                        DB::commit();
                        $id = $vente->id;
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

    public function generatePDF($id)
    {
        $vente = Vente::find($id);
        if($vente!=null)
        {
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, true);
        // dd($data);
         $pdf = PDF::loadView("pdf.ventesold", $data);
        //  $measure = array(0,0,225.772,650.197);
            // return $pdf->setPaper($measure, 'orientation')->stream();
             return $pdf->stream();
        }else{
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, false);
            return view('notfound');
        }
    }
    public function generatePDFRetour($id)
    {
        $bon_retour = BonRetour::find($id);
        if($bon_retour!=null)
        {
         $data = Outil::getOneItemWithGraphQl("bon_retours", $id, true);
         //dd($data);
         $pdf = PDF::loadView("pdf.ticket-retour", $data);
        $measure = array(0,0,225.772,650.197);
        return $pdf->setPaper($measure, 'orientation')->stream();
            //  return $pdf->stream();
        }else{
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, false);
            return view('notfound');
        }
    }
    public function generatePDF2()
    {
            // Calculez le montant total de la caisse à la fermeture (par exemple, en ajoutant les montants des consultations non facturées)
            // $totalCaisse = $request->montant_total;
            $errors =null;
            $montant = 0;
            $results = [];
            $count = DB::table('cloture_caisses')->count();
            if ($count === 0) {
                $data = DB::table('ventes')
                    ->select('*')
                    ->where('statut', '=', false)
                    ->orderBy('created_at')
                    ->get()
                    ->toArray();
                    $results['data'] = $data;
                    $results['date_situation'] = now()->format('Y-m-d H:i:s');
                //dd($results);
            } else {
                $data = DB::table('ventes')
                    ->select('*')
                    ->where('statut', '=', false)
                    ->where(function ($query) {
                        $query->where('created_at', '>=', function ($subQuery) {
                            $subQuery->select('date_fermeture')
                                ->from('cloture_caisses')
                                ->orderByDesc('date_fermeture')
                                ->limit(1);
                        });
                    })
                    ->where('created_at', '<=', now())
                    ->orderBy('created_at')
                    ->get();

                    $latestClosureDate = DB::table('cloture_caisses')
                    ->select(DB::raw('MAX(date_fermeture) AS latest_date_fermeture'))
                    ->whereNotNull('date_fermeture')
                    ->first();
                    $results['data'] = $data;
                    $results['derniere_date_fermeture'] = $latestClosureDate->latest_date_fermeture;
                    $results['date_situation'] = now()->format('Y-m-d H:i:s');
            }   
        $pdf = PDF::loadView("pdf.situation-pdf",$results);
        return $pdf->stream();
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
    public function delete($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $errors = null;
                $data = 0;

                if ($id) {
                    $vente = Vente::with('vente_produits')->find($id);
                    if ($vente != null) {
                        if (!(Carbon::now() > Carbon::parse($vente->created_at)->addDay())) {
                            $vente->delete();
                            $vente->forceDelete();
                            $data = 1;
                        } else {
                            $errors = "La Date est dépassée de 1 Jour";
                        }
                    } else {
                        $data = 0;
                        $errors = "Vente inexistante";
                    }
                } else {
                    $errors = "Données manquantes";
                }

                if (isset($errors)) {
                    throw new \Exception('{"data": null, "errors": "' . $errors . '" }');
                }
                return response('{"data":' . $data . ', "errors": "' . $errors . '" }')->header('Content-Type', 'application/json');
            });
        } catch (\Exception $e) {
            return response($e->getMessage())->header('Content-Type', 'application/json');
        }
    }

     /**
     * Search for a id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search($id)
    {
        //
        return Vente::where('id',$id)->get();
    }
}