<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Depot,InitialDepot,Outil,LigneTransfert,Produit};

use Illuminate\Support\Facades\DB;

class DepotController extends Controller
{

    private $queryName = "initial_depots";

    public function save(Request $request)
    {
        try {
            $errors = null;

            if (empty($request->initial_depot_id)) {
                $errors = "Renseignez le dépôt concerné'";
            }

            $details = json_decode(json_encode($request->details), true);
            DB::beginTransaction();
            foreach ($details as $detail) {
                $exists = Depot::where('initial_depot_id', $request->initial_depot_id)
                    ->where('produit_id', $detail['produit_id'])
                    ->exists();

                if ($exists) {
                    continue;
                }
                $item = new Depot();
                $item->produit_id       = $detail['produit_id'];
                $item->pa               = 0;
                $item->stock            = $detail['stock'];
                $item->initial_depot_id = $request->initial_depot_id;
                $item->save();
            }
            DB::commit();
            if (isset($errors))
            {
                throw new \Exception($errors);
            }
            $id = $request->initial_depot_id;
            return Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);

        } catch (exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }


    public function transfert_depot(Request $request)
    {
        try 
        {
            DB::beginTransaction();
            $errors = null;
            $str_json = json_encode($request->details);
            $details = json_decode($str_json, true);
            $createdLines = [];
                foreach ($details as $detail)
                {
                    // Soustraire Source Depot
                    $depot_source = Depot::where('produit_id',$detail['produit_id'])->where('initial_depot_id',$detail['source_depot_id'])->first();
                    if (!$depot_source) {
                        $errors = "ce produit n'existe pas dans le depot source ! Creer d'abord le produit dans le depot source";
                    }
                    if (isset($errors))
                    {
                        throw new \Exception($errors);
                    }
                    if ($depot_source->stock < $detail['qte_transfere']) {
                        $errors = "La quantite en stock du produit est insufisant";
                    }
                    if ($depot_source)
                    {
                        $depot_source->stock = $depot_source->stock - $detail['qte_transfere'];
                        $depot_source->save();
                    }

                    // Additionner Depot Destination
                    $depot_destination = Depot::where('produit_id',$detail['produit_id'])->where('initial_depot_id',$detail['destination_depot_id'])->first();
                    if (!$depot_destination) {
                        $errors = "ce produit n'existe pas dans le depot destination ! Creer d'abord le produit dans le depot destination";
                    }
                    if (isset($errors))
                    {
                        throw new \Exception($errors);
                    }
                    if ($depot_destination)
                    {
                        $depot_destination->stock = $depot_destination->stock + $detail['qte_transfere'];
                        $depot_destination->save();
                    }
                    if (!isset($errors)) 
                    {
                        $item                       = new LigneTransfert();
                        $item->produit_id           = $detail['produit_id'];
                        $item->qte_transfere        = $detail['qte_transfere'];
                        $item->source_depot         = $detail['source_depot_id'];
                        $item->destination_depot    = $detail['destination_depot_id'];
                        $item->user_id              = 8;
                        $item->save();
                        $createdLines[] = $item;
                    }
                    if (isset($errors))
                    {
                        throw new \Exception($errors);
                    }
                }
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transfert effectué avec succès',
                    'data' => $createdLines
                ], 201);
                
            } catch (exception $e) {
                DB::rollback();
                return $e->getMessage();
            }
    }

    public function syncProduitsDepots()
    {
        $produits = Produit::all();

        foreach ($produits as $produit) {
            $depot = Depot::where('produit_id', $produit->id)
                      ->where('initial_depot_id', 1) 
                      ->first();

            if ($depot) {
                $depot->update([
                    'stock' => $produit->qte,
                ]);
            } else {
                Depot::create([
                    'produit_id'        => $produit->id,
                    'stock'             => $produit->qte,
                    'pa'                => $produit->pa ?? 0,
                    'limite'            => $produit->limite ?? null,
                    'initial_depot_id'  => 1, // DEPOT PHARMACIE
                ]);
            }
        }

        return response()->json([
            'message' => 'Synchronisation des produits et dépôts terminée.',
        ]);
    }

    public function save_intial_depot(Request $request)
    {
        try 
        {
                $errors =null;
                $item = new InitialDepot();
                if (!empty($request->id))
                {
                    $item = InitialDepot::find($request->id);
                }
                $item->nom_depot = $request->nom_depot;
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