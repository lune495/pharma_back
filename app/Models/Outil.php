<?php

namespace App\Models;


use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use App\Exports\DatasExport;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use MPDF;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Mail;
use App\Mail\Maileur;

class Outil extends Model
{

    public static $queries = array(
        "produits"      => " id,designation,description,qte,pa,pv,limite,famille_id,famille{id,nom},depots{id,produit_id,stock,limite,pa}",
        "ventes"        => " id,montant,montantencaisse,monnaie,qte,user_id,user{id,name,email,role_id,role{id,nom}},vente_produits{id,prix_vente,produit_id,produit{id,designation,qte,pv},vente_id}",
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
    public static function formatdate()
    {
        return "Y-m-d H:i:s";
    }
    public static function getTotalvente($from,$to)
    {
         $Totalvent=Vente::whereBetween('created_at', array($from, $to))->count();
        return   $Totalvent;
    }

    public static function getCavente($from,$to)
    {
        $sommetotal = DB::select(DB::raw("select (select coalesce(sum(p.pv*vp.qte),0) from vente_produits as vp,produits as p,ventes as v where  vp.created_at >= ?  and vp.vente_id = v.id  and vp.created_at <= ? and vp.produit_id=p.id )
        as solde "),[$from, $to])[0]->solde;
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
