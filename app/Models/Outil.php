<?php

namespace App\Models;


use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use App\Exports\DatasExport;
use Barryvdh\DomPDF\Facade as PDF;
//use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use MPDF;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Mail;
use App\Mail\Maileur;   
use \NumberFormatter;
class Outil extends Model
{
    public static $queries = array(
        "produits"                   => " id,image,code,designation,description,qte,pa,pv,limite,famille_id,famille{id,nom},depots{id,produit_id,stock,limite,pa}",
        "ventes"                     => " id,nom_complet,numero,paye,montant,montant_ht,montant_ttc,montant_taxe,statut,montant_avec_remise,remise_total,qte,created_at,created_at_fr,taxe{id,value},client{nom_complet,telephone,adresse},montantencaisse,monnaie,user_id,user{id,name,role{id,nom}},vente_produits{id,remise,pu_net,montant_net,montant_remise,qte,prix_vente,total,produit{id,code,designation,qte,pv}}",
        "proformas"                  => " id,numero,montant,montant_ht,montant_ttc,montant_taxe,montant_avec_remise,remise_total,qte,created_at,created_at_fr,taxe{id,value},client{nom_complet,telephone,adresse},user_id,user{id,name,role{id,nom}},proforma_produits{id,remise,pu_net,montant_net,montant_remise,qte,prix_vente,total,produit{id,code,designation,qte,pv}}",
        "users"                      => " id,name,email,role{id,nom}",
        "inventaires"                => " id,ref,user{id,name},ligne_inventaires{id,produit{id,designation},quantite_reel,quantite_theorique,diff_inventaire}",
        "sortiestocks"               => " id,ref,user{id,name},ligne_bon_stocks{id,produit{id,designation},quantite,quantite_stock}",
        "bon_retours"                => " id,ref,nom_client,user{email},ligne_bon_retours{id,produit{id,designation,pv},quantite_retour,created_at},created_at",
        "remises"                    => " id,value",
        "approvisionnements"         => " id,user_id,user{name},montant,statut,numero,qte_total_appro,fournisseur_id,fournisseur{id,nom_complet,telephone,adresse},ligne_approvisionnements{id,produit_id,produit{id,designation,pa,pv,qte,famille_id,famille{id,nom}},quantity_received,created_at,created_at_fr,updated_at,updated_at_fr},created_at,created_at_fr,type_appro",
    );

    public static function redirectgraphql($itemName, $critere,$liste_attributs)
    {
        $path='{'.$itemName.'('.$critere.'){'.$liste_attributs.'}}';
        return redirect('graphql?query='.urlencode($path));
    }

    public static function getResponseError(\Exception $e)
    {
        return response()->json(array(
            'errors'          => [config('env.APP_ERROR_API') ? $e->getMessage() : config('env.MSG_ERROR')],
            'errors_debug'    => [$e->getMessage()],
            'errors_line'    => [$e->getLine()],
        ));
    }
    public static function getOneItemWithGraphQl($queryName, $id_critere, $justone = true)
    {
        $guzzleClient = new \GuzzleHttp\Client([
            'defaults' => [
                'exceptions' => true
            ]
        ]);
        $name_env = self::getAPI();
        $critere = (is_numeric($id_critere)) ? "id:{$id_critere}" : $id_critere;
        $queryAttr = Outil::$queries[$queryName];
        $response = $guzzleClient->get("{$name_env}graphql?query={{$queryName}({$critere}){{$queryAttr}}}");
        $data = json_decode($response->getBody(), true);
        // dd($data);
        return ($justone) ? $data['data'][$queryName][0] : $data;
    }
    public static function setParametersExecution()
    {
        ini_set('max_execution_time', -1);
        ini_set('max_input_time', -1);
        ini_set('pcre.backtrack_limit', 50000000000);
        ini_set('memory_limit',-1);
    }
    public static function getAPI()
    {
        return config('env.APP_URL');
    }
    public static function formatdate()
    {
        return "Y-m-d H:i:s";
    }
    public static function getTotalvente($from,$to)
    {
         $Totalvent=Vente::whereBetween('created_at', array($from, $to))->count();
        return   $Totalvent;
    }
    public static function getCaProduit($id,$from,$to)
    {
          $ca = DB::select(DB::raw("select (select coalesce(sum(vp.prix_vente*vp.qte),0) from vente_produits as vp,produits as p,ventes as v where vp.produit_id = ? and vp.vente_id = v.id and vp.produit_id=p.id and vp.created_at >= ? and vp.created_at <= ?  )
        as ca "),[$id,$from,$to])[0]->ca;
        $pa = DB::select(DB::raw("select (SELECT COALESCE(SUM(p.pa),0) FROM `vente_produits` as vp,`produits` as p,`ventes` as v WHERE vp.`produit_id` = ? and vp.`vente_id` = v.id and vp.`produit_id`=p.id and vp.created_at >= ? and vp.created_at <= ?) 
        as pa"),[$id,$from,$to])[0]->pa;
        return  $ca - $pa;
    }
    public static function premereLettreMajuscule($val)
    {
        return ucfirst($val);
    }
    //Formater le prix
    public static function formatPrixToMonetaire($nbre, $arrondir = false, $avecDevise = false)
    {
        //Ajouté pour arrondir le montant
        if ($arrondir == true) {
            $nbre = Outil::enleveEspaces($nbre);
            $nbre = round($nbre);
        }
        $rslt = "";
        $position = strpos($nbre, '.');
        if ($position === false) {
            //---C'est un entier---//
            //Cas 1 000 000 000 Ã  9 999 000
            if (strlen($nbre) >= 9) {
                $c = substr($nbre, -3, 3);
                $b = substr($nbre, -6, 3);
                $d = substr($nbre, -9, 3);
                $a = substr($nbre, 0, strlen($nbre) - 9);
                $rslt = $a . ' ' . $d . ' ' . $b . ' ' . $c;
            } //Cas 100 000 000 Ã  9 999 000
            elseif (strlen($nbre) >= 7 && strlen($nbre) < 9) {
                $c = substr($nbre, -3, 3);
                $b = substr($nbre, -6, 3);
                $a = substr($nbre, 0, strlen($nbre) - 6);
                $rslt = $a . ' ' . $b . ' ' . $c;
            } //Cas 100 000 Ã  999 000
            elseif (strlen($nbre) >= 6 && strlen($nbre) < 7) {
                $a = substr($nbre, 0, 3);
                $b = substr($nbre, 3);
                $rslt = $a . ' ' . $b;
                //Cas 0 Ã  99 000
            } elseif (strlen($nbre) < 6) {
                if (strlen($nbre) > 3) {
                    $a = substr($nbre, 0, strlen($nbre) - 3);
                    $b = substr($nbre, -3, 3);
                    $rslt = $a . ' ' . $b;
                } else {
                    $rslt = $nbre;
                }
            }
        } else {
            //---C'est un décimal---//
            $partieEntiere = substr($nbre, 0, $position);
            $partieDecimale = substr($nbre, $position, strlen($nbre));
            //Cas 1 000 000 000 Ã  9 999 000
            if (strlen($partieEntiere) >= 9) {
                $c = substr($partieEntiere, -3, 3);
                $b = substr($partieEntiere, -6, 3);
                $d = substr($partieEntiere, -9, 3);
                $a = substr($partieEntiere, 0, strlen($partieEntiere) - 9);
                $rslt = $a . ' ' . $d . ' ' . $b . ' ' . $c;
            } //Cas 100 000 000 Ã  9 999 000
            elseif (strlen($partieEntiere) >= 7 && strlen($partieEntiere) < 9) {
                $c = substr($partieEntiere, -3, 3);
                $b = substr($partieEntiere, -6, 3);
                $a = substr($partieEntiere, 0, strlen($partieEntiere) - 6);
                $rslt = $a . ' ' . $b . ' ' . $c;
            } //Cas 100 000 Ã  999 000
            elseif (strlen($partieEntiere) >= 6 && strlen($partieEntiere) < 7) {
                $a = substr($partieEntiere, 0, 3);
                $b = substr($partieEntiere, 3);
                $rslt = $a . ' ' . $b;
                //Cas 0 Ã  99 000
            } elseif (strlen($partieEntiere) < 6) {
                if (strlen($partieEntiere) > 3) {
                    $a = substr($partieEntiere, 0, strlen($partieEntiere) - 3);
                    $b = substr($partieEntiere, -3, 3);
                    $rslt = $a . ' ' . $b;
                } else {
                    $rslt = $partieEntiere;
                }
            }
            if ($partieDecimale == '.0' || $partieDecimale == '.00' || $partieDecimale == '.000') {
                $partieDecimale = '';
            }
            $rslt = $rslt . '' . $partieDecimale;
        }
        if ($avecDevise == true) {
            $formatDevise = Outil::donneFormatDevise();
            $rslt = $rslt . '' . $formatDevise;
        }
        return $rslt;
    }
    public static function convertNumber($num)
    {
        $f = new NumberFormatter("fr", NumberFormatter::SPELLOUT);
        return  ucfirst("{$f->format($num)} FRANCS CFA");
    }
    public static function donneFormatDevise()
    {
        $retour = ' F CFA';
        return $retour;
    }

    public static function toUpperCase($inputString) {
        return strtoupper($inputString);
    }

    public static function getCavente($from,$to)
    {
        $sommetotal = DB::select(DB::raw("select (select coalesce(sum(vp.prix_vente*vp.qte),0) from vente_produits as vp,produits as p,ventes as v where  vp.created_at >= ?  and vp.vente_id = v.id  and vp.created_at <= ? and vp.produit_id=p.id and v.statut is false )
        as solde "),[$from, $to])[0]->solde;
        $sommetotal = Outil::formatPrixToMonetaire($sommetotal, false, true);
        return  $sommetotal;


       /*  $allventes= Vente::whereBetween('created_at', array($from, $to))->get();
        $allbon = Bon::whereBetween('created_at', array($from, $to))->get();
        $totalallvente = 0 ;
        foreach ($allventes as $onevente)
        {
            $paiemevent = PaiementVente::where('vente_id',$onevente->id)->get();
            if(count($paiemevent) >0)
            {
                foreach ($paiemevent as $onepaiementvente)
                {
                    $totalallvente+=$onepaiementvente->montant;
                }
            }
        }
        foreach ($allbon as $one_bon)
        {
            $ventebon = Vente::where('bon_id',$one_bon->id)->first();
            if(!isset($ventebon))
            {
                $totalallvente += $one_bon->montant;
            }
        }
        return $totalallvente ; */
    }

    public static function getTotalproduitvente($from,$to)
    {

        return VenteProduit::whereBetween('created_at', array($from, $to))
        ->groupBy('produit_id')
        ->count();
    }

    public static function getOperateurLikeDB()
    {
        return config('database.default')=="mysql" ? "like" : "ilike";
    }
}
/*select * from reservations where programme_id in (select id from programmes where id=1112 and ((quotepart_pourcentage is not null && quotepart_pourcentage!=0) or (quotepart_valeur is not null && quotepart_valeur!=0)));*/
