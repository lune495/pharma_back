<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
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
                $item->designation = $request->designation;
                $item->description = $request->description;
                $item->famille_id = $request->famille_id;
                $item->image = $request->image;
                $item->pa = $request->pa;
                $item->pv = $request->pv;
                $item->limite = $request->limite;
                $item->qte = $request->qte;
                try{
                    if (!isset($errors)) 
                    {
                        $item->save();
                        return $item;
                    }
                }
                catch (\Exception $e)
                {
                    throw new \Exception('{"data": null, "errors": "'.$e->getMessage().'" }');
                }
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
        return Produit::find($id);
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
        $produit = Produit::find($id);
        $produit->update($request->all());
        return $produit;
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
