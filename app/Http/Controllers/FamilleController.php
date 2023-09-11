<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Famille;
use Illuminate\Support\Facades\DB;

class FamilleController extends Controller
{
    //
    public function save(Request $request)
    {
        try 
        {
                $errors =null;
                $item = new Famille();
                if (!empty($request->id))
                {
                    $item = Famille::find($request->id);
                }
                if (empty($request->nom))
                {
                    $errors = "Renseignez le nom de la categorie";
                }
                $item->nom = $request->nom;
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
                    $famille = Famille::find($id);
                    if ($famille!=null)
                    {
                        if(count($famille->produits) > 0)
                        {
                            $data = 0;
                            $errors = "Cette categorie  est déjà liée à un produit, impossible de le supprimer";
                        }
                        else
                        {
                            $famille->delete();
                            $famille->forceDelete();
                            $data = 1;
                        }
                    }
                    else
                    {
                        $data = 0;
                        $errors = "categorie inexistant";
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
    }
}
