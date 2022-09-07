<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Produit,Outil};
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    private $queryName = "produits";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return Produit::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Produit::create($request->all());
    }

    public function save(Request $request)
    {
        try 
        {
                $errors =null;
                $item = new Produit();
                if (!empty($request->id))
                {
                    $item = Produit::find($request->id);
                }
                if (empty($request->designation))
                {
                    $errors = "Renseignez la designation";
                }
                if (empty($request->famille_id))
                {
                    $errors = "Renseignez la categorie du produit";
                }
                
                if (empty($request->qte))
                {
                    $errors = "Renseignez la quantite du produit";
                }
                if (empty($request->pa))
                {
                    $errors = "Renseignez le prix d'achat";
                }
                if (empty($request->pv))
                {
                    $errors = "Renseignez le prix de vente";
                }
                $item->designation = $request->designation;
                $item->code = $request->code;
                $item->description = $request->description;
                $item->famille_id = $request->famille_id;
                $item->image = $request->image;
                $item->pa = $request->pa;
                $item->pv = $request->pv;
                $item->limite = $request->limite;
                $item->qte = $request->qte;
                    if (!isset($errors)) 
                    {
                        $item->save();
                        $id = $item->id;
                        return  Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
                    }
                    if (isset($errors))
                    {
                        throw new \Exception('{"data": null, "errors": "'. $errors .'" }');
                    }
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
        return Produit::find($id);
    }

    public function list()
    {
        //
        $top_produit =  DB::select(DB::raw("select produits.designation, sum(vente_produits.qte) as Qte from vente_produits inner join produits on vente_produits.produit_id = produits.id group by produits.designation Order by sum(vente_produits.qte) desc limit 5"));
        return $top_produit;
    }

    public function list_meilleur_client()
    {
        //
        $top_client =  DB::select(DB::raw("select clients.nom_complet, sum(ventes.montant) as montant from ventes inner join clients on ventes.client_id = clients.id where ventes.client_id is not null group by clients.nom_complet Order by sum(ventes.montant) desc limit 5"));
        return $top_client;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try
        {
            return DB::transaction(function () use ($id)
            {
                $errors = null;
                $data = 0;
                if($id)
                {

                    $item = Produit::with('vente_produits')->find($id);
                    //dd($item->declinaisons);
                    if ($item!=null)
                    {
                        if (count($item->vente_produits)==0)
                        {
                            $item->delete();
                            $item->forceDelete();
                            $data = 1;
                        }
                        else
                        {
                            $data = 0;
                            $errors = "Ce produit fait déjà partie d'une vente";
                        }
                    }
                    else
                    {
                        $data = 0;
                        $errors = "Produit inexistant";
                    }
                }
                else
                {
                    $errors = "Données manquantes";
                }

                if (isset($errors))
                {
                    throw new \Exception('{"data": null, "errors": "'. $errors .'" }');
                }
                return response('{"data":' . $data . ', "errors": "'. $errors .'" }')->header('Content-Type','application/json');
            });
        }
        catch (\Exception $e)
        {
            return response($e->getMessage())->header('Content-Type','application/json');
        }
        // if($id)
        //         {

        //             $item = Produit::with('vente_produits','ligne_approvisionnements','produit_complementaires')->find($id);
        //             //dd($item->declinaisons);
        //             if ($item!=null)
        //             { 
        //                 if (count($item->vente_produits)==0 && count($item->ligne_approvisionnements)==0 && count($item->produit_complementaires)==0)
        //                 {
        //                     $item->delete();
        //                     $item->forceDelete();
        //                     $data = 1;
        //                 }
        //                 else
        //                 {
        //                     $data = 0;
        //                     $errors = "Ce produit fait déjà partie d'une vente ou d'une approvisionnement ou est lié a un produit complementaire";
        //                 }
        //             }
        //             else
        //             {
        //                 $data = 0;
        //                 $errors = "Produit inexistant";
        //             }
        //         }
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
