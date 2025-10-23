<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournisseur;
use Illuminate\Support\Facades\DB;

class FournisseurController extends Controller
{

    public function save(Request $request)
    {
        //
         try 
        {
            $errors =null;
            $item = new Fournisseur();
            if (!empty($request->id))
            {
                $item = Fournisseur::find($request->id);
            }
            if (empty($request->nom_complet))
            {
                $errors = "Renseignez le nom du fournisseur";
            }
            $item->nom_complet = $request->nom_complet;
            $item->email = $request->email;
            $item->adresse = $request->adresse;
            $item->telephone = $request->telephone;
            $item->adresse = $request->adresse;
            $item->alias = $request->alias;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
         try {
            return DB::transaction(function () use ($id) {
                $errors = null;
                $data = 0;

               if($id)
                {
                    $fournisseur = Fournisseur::find($id);
                    if ($fournisseur!=null)
                    {
                        if(count($fournisseur->depots) > 0)
                        {
                            $data = 0;
                            $errors = "Ce fournisseur est déjà liée à un appro";
                        }
                        else
                        {
                            $client->delete();
                            $client->forceDelete();
                            $data = 1;
                        }
                    }
                      else
                    {
                        $data = 0;
                        $errors = "client inexistant";
                    }
                }
                else
                {
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
}
