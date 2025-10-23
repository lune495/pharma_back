<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Depot,InitialDepot,Outil,LigneTransfert,Produit};

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \PDF;
class DepotController extends Controller
{

    private $queryName = "initial_depots";

    // public function save(Request $request)
    // {
    //     try {
    //         $errors = null;
    //         DB::beginTransaction();
    //         if (empty($request->initial_depot_id)) {
    //             $errors = "Renseignez le dépôt concerné'";
    //         }

    //         $initial_depot = InitialDepot::find($request->initial_depot_id);
    //         if (!isset($initial_depot)) {
    //             $errors = "Depot inexistant";
    //         }
    //         $details = json_decode(json_encode($request->details), true);
    //         foreach ($details as $detail) {
    //             $exists = Depot::where('initial_depot_id', $initial_depot->id)
    //                 ->where('produit_id', $detail['produit_id'])
    //                 ->exists();

    //             if ($exists) {
    //                 continue;
    //             }
    //             $item = new Depot();
    //             $item->produit_id       = $detail['produit_id'];
    //             $item->pa               = 0;
    //             $item->stock            = $detail['stock'];
    //             $item->initial_depot_id = $initial_depot->id;
    //             $item->save();
    //             if ($item->save() && $initial_depot->nom_depot == 'MAGASIN') 
    //             {
    //                 $produit = Produit::where('id', $detail['produit_id'])->where('initial_depot_id', $initial_depot->id)->first();
    //                 $produit->stock_magasin = $detail['quantite'];
    //                 $produit->save();
    //             }elseif ($item->save() && $initial_depot->nom_depot == 'PHARMACIE') 
    //             {
    //                 $produit = Produit::where('id', $detail['produit_id'])->where('initial_depot_id', $initial_depot->id)->first();
    //                 $produit->stock_pharmacie = $detail['quantite'];
    //                 $produit->save();
    //             }
    //         }
    //         DB::commit();
    //         if (isset($errors))
    //         {
    //             throw new \Exception($errors);
    //         }
    //         $id = $request->initial_depot_id;
    //         return Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);

    //     } catch (exception $e) {
    //         DB::rollback();
    //         return $e->getMessage();
    //     }
    // }


    public function transfert_depot(Request $request)
    {
        // dd($request->all());
        try 
        {
            DB::beginTransaction();
            $errors = null;
            $stock = 0;
            $str_json = json_encode($request->details);
            $details = json_decode($str_json, true);
            $createdLines = [];
                foreach ($details as $detail)
                {
                    // Soustraire Source Depot
                    $depot_source = Depot::where('produit_id',$detail['produit_id'])->where('initial_depot_id',$detail['source_depot_id'])->first();
                    $produit = Produit::find($detail['produit_id']);

                    if ($detail['source_depot_id'] == $detail['destination_depot_id']) 
                    {
                        $errors = "Le depot source et le depot de destination ne peuvent pas être identiques";
                    }
                    if (isset($produit) && $detail['source_depot_id'] == 1)
                    {
                        $stock = $produit->stock_pharma;
                    }
                    elseif (isset($produit) && $detail['source_depot_id'] == 2)
                    {
                        $stock = $produit->stock_magasin;
                    }
                    if (!$depot_source && isset($produit))
                    {
                        $depot = new Depot();
                        $depot->produit_id = $detail['produit_id'];
                        $depot->stock = $stock ?? 0;
                        $depot->pa = 0;
                        $depot->limite = null;
                        $depot->initial_depot_id = $detail['source_depot_id'];
                        $depot->save();
                        // $errors = "ce produit n'existe pas dans le depot source ! Creer d'abord le produit dans le depot source";
                    }
                    if ($stock == 0) {
                        $errors = "Le stock est vide dans le depot source";
                    }
                    if ($stock < $detail['qte_transfere']) 
                    {
                        $errors = "La quantite en stock est de {$stock} Impossible de transferer plus de cette quantité";
                    }
                    if (isset($errors)){
                        throw new \Exception($errors);
                    }
                    
                    if ($depot_source)
                    {
                        $depot_source->stock = $stock - $detail['qte_transfere'];
                        $depot_source->save();

                        //Update Stock Produit
                        if (isset($produit) && $detail['source_depot_id'] == 1)
                        {
                            $produit->stock_pharma = $produit->stock_pharma - $detail['qte_transfere'];
                            $produit->save();
                        }
                        if (isset($produit) && $detail['source_depot_id'] == 2)
                        {
                            $produit->stock_magasin = $produit->stock_magasin - $detail['qte_transfere'];
                            $produit->save();
                        }
                    }

                    // Additionner Depot Destination
                    $depot_destination = Depot::where('produit_id',$detail['produit_id'])->where('initial_depot_id',$detail['destination_depot_id'])->first();
                    if (!$depot_destination) 
                    {
                        $depot = new Depot();
                        $depot->produit_id = $detail['produit_id'];
                        $depot->stock = $stock ?? 0;
                        $depot->pa = 0;
                        $depot->limite = null;
                        $depot->initial_depot_id = $detail['destination_depot_id'];
                        $depot->save();
                        // $errors = "ce produit n'existe pas dans le depot destination ! Creer d'abord le produit dans le depot destination";
                    }
                    if (isset($errors))
                    {
                        throw new \Exception($errors);
                    }
                    if ($depot_destination)
                    {
                        $depot_destination->stock = $stock + $detail['qte_transfere'];
                        $depot_destination->save();

                        //Update Stock Produit
                        if (isset($produit) && $detail['destination_depot_id'] == 1)
                        {
                            $produit->stock_pharma = $produit->stock_pharma + $detail['qte_transfere'];
                            $produit->save();
                        }
                        if (isset($produit) && $detail['destination_depot_id'] == 2)
                        {
                            $produit->stock_magasin = $produit->stock_magasin + $detail['qte_transfere'];
                            $produit->save();
                        }
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
            // 1 = PHARMACIE (Initial Depot)
            // $depot = Depot::where('produit_id', $produit->id)->where('initial_depot_id', 1)->first();
            // 2 = MAGASIN (Depot de Destination)
            $depot = Depot::where('produit_id', $produit->id)->where('initial_depot_id', 2)->first();

            if (!$depot) 
            {
                $depot = new Depot();
                $depot->produit_id = $produit->id;
                $depot->stock = 0;
                $depot->pa = $produit->pa ?? 0;
                $depot->limite = $produit->limite ?? null;
                $depot->initial_depot_id = 2;
                $depot->save();
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


    public function getMouvementsProduits($initial_depot_id)
    {

        $produits = Produit::all();
        // Récupération des dates de début et de fin
        $dateDebut = Carbon::parse('first day of January')->startOfDay();  // 1er janvier de l'année en cours
        $dateFin = Carbon::now();  // Date actuelle
        $data = $produits->map(function ($produit) use ($initial_depot_id, $dateDebut, $dateFin) {
            // Calcul du stock initial selon le dépôt
            $stock = 0;
            if ($initial_depot_id == 1) {
                $stock = $produit->stock_initial_pharma ?? 0;
            } elseif ($initial_depot_id == 2) {
                $stock = $produit->stock_initial_magasin ?? 0;
            }
            // Total des approvisionnements (entrées) filtré par date
            $totalEntrees = DB::table('ligne_approvisionnements')
                ->where('produit_id', $produit->id)
                ->where('initial_depot_id', $initial_depot_id)
                ->whereBetween('created_at', [$dateDebut, $dateFin])  // Filtre par date
                ->sum('quantity_received');

            // Total des sorties dans ligne_sortie_stocks filtré par date
            $totalSorties = DB::table('ligne_sortie_stocks')
                ->where('produit_id', $produit->id)
                ->where('initial_depot_id', $initial_depot_id)
                ->whereBetween('created_at', [$dateDebut, $dateFin])  // Filtre par date
                ->sum('quantite');

            // Transferts sortants (source_depot = ton dépôt) filtré par date
            $transfertsSortants = DB::table('ligne_transferts')
                ->where('produit_id', $produit->id)
                ->where('source_depot', $initial_depot_id)
                ->whereBetween('created_at', [$dateDebut, $dateFin])  // Filtre par date
                ->sum('qte_transfere');

            // Transferts entrants (destination_depot = ton dépôt) filtré par date
            $transfertsEntrants = DB::table('ligne_transferts')
                ->where('produit_id', $produit->id)
                ->where('destination_depot', $initial_depot_id)
                ->whereBetween('created_at', [$dateDebut, $dateFin])  // Filtre par date
                ->sum('qte_transfere');

            // Sorties des ventes (initial_depot_id = 1) filtré par date
            $ventesSorties = 0;
            if ($initial_depot_id == 1) { // Ne prendre en compte les ventes que si $initial_depot_id est 1
                $ventesSorties = DB::table('vente_produits')
                    ->where('produit_id', $produit->id)
                    ->where('initial_depot_id', 1) // Vente venant du dépôt 1
                    ->whereBetween('created_at', [$dateDebut, $dateFin])  // Filtre par date
                    ->sum('qte');
            }

            // Mise à jour des totaux
            $totalEntrees += $transfertsEntrants;
            $totalSorties += $transfertsSortants + $ventesSorties; // Ajoute les sorties des ventes si applicable

            //  Calcul du stock actuel :
            // Quantité initiale (dans la table produits) + entrées - sorties
            $stockActuel = $stock + ($totalEntrees - $totalSorties);

            return [
                'produit_id' => $produit->id,
                'nom' => $produit->designation ?? null,
                'quantite_initiale' => $stock,
                'total_entrees' => (int) $totalEntrees,
                'total_sorties' => (int) $totalSorties,
                'transferts_entrants' => (int) $transfertsEntrants,
                'transferts_sortants' => (int) $transfertsSortants,
                'ventes_sorties' => (int) $ventesSorties,
                'stock_actuel' => (int) $stockActuel,
            ];
        });
        $pdf = PDF::loadView("pdf.stat_stock_par_depot", [
            'stocks' => $data,
            'dateDebut' => $dateDebut->format('d/m/Y'),
            'dateFin' => $dateFin->format('d/m/Y'),
            'depot' => $initial_depot_id == 1 ? 'PHARMACIE' : 'MAGASIN'
        ]);

        return $pdf->stream();  
    }

}