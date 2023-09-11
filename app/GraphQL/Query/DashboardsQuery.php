<?php

namespace App\GraphQL\Query;
use Carbon\Carbon;
use  App\Models\Outil;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;



class DashboardsQuery extends Query
{
    protected $attributes = [
        'name' => 'dashboards'
    ];

    public function type():type
    {
        return Type::listOf(GraphQL::type('Dashboard'));
    }

    public function args():array
    {
        return
        [
            'date_day'           => ['type' => Type::string()],
            'date_month'         => ['type' => Type::string()],
            'date_year'          => ['type' => Type::string()],
            'current_day'        => ['type' => Type::boolean()],
            'current_month'      => ['type' => Type::boolean()],
            'produit_id'         => ['type' => Type::int()],
            'date_start'         => ['type' => Type::string()],
            'date_end'           => ['type' => Type::string()],
        ];
    }

   public function resolve($root, $args)
    {


        $from = isset($args['date_start']) ? date($args['date_start']) : date('Y-m-d');
        $to = isset($args['date_end']) ? date($args['date_end']) : date('Y-m-d');
        $from = (strpos($from, '/') !== false) ? Carbon::createFromFormat('d/m/Y', $from)->format('Y-m-d') : $from;
        $to = (strpos($to, '/') !== false) ? Carbon::createFromFormat('d/m/Y', $to)->format('Y-m-d') : $to;
        $from = $from.' 00:00:00';
        $to = $to.' 23:59:59';


        if (isset($args['current_day']) || isset($args['date_day']))
        {
            $thisday = isset($args['date_day']) ? date($args['date_day']) : date('Y-m-d');
            // Eventuellement la date fr
            $thisday = (strpos($thisday, '/') !== false) ? Carbon::createFromFormat('d/m/Y', $thisday)->format('Y-m-d') : $thisday;
            $debut = date($thisday.' 00:00:00');
            $fin = date($thisday.' 23:59:59'); 
        }
        else if (isset($args['current_month']) || isset($args['date_month']))
        {
            $year = date('Y');
            $today = isset($args['date_month']) ? date($year.'-'.$args['date_month']) : date('Y-m');
            $month = isset($args['date_month']) ? date($args['date_month']) : date('m');
            $dim = date( "t", mktime(0, 0, 0, $month, 1, $year));

            $debut = date($today.'-01 00:00:00');
            $fin = date($today.'-'.$dim.' 23:59:59');
        }
        else if (isset($args['current_year']) || isset($args['date_year']))
        {
            $today = isset($args['date_year']) ? date($args['date_year']) : date('Y');
            $debut = date($today.'-01-01 00:00:00');
            $fin = date($today.'-12-31 23:59:59');
        }
        else
        {
            $today = date('Y-m-d');
            $debut = date($today.' 00:00:00');
            $fin = date($today.' 23:59:59');
        }
        $caproduit = 0;
        if(isset($args['produit_id']))
        {
            $caproduit = Outil::getCaProduit($args['produit_id'],$from,$to);
        }
        
        //********JOUR***********************
      
        $nbVenteJour = Outil::getTotalvente($debut,  $fin );
        $nbproduitjour = Outil::getTotalproduitvente($debut,  $fin );
        $Caventejour = Outil::getCavente($debut,  $fin );
        
       // ********MOIS***********************

       $nbVentemois = 0;
       $Caventemois= 0;
       $nbproduitmois = 0;
       $nbVenteannee = 0;
       $Caventeannee = 0;
       $nbproduitannee = 0;

       if ( isset($args['date_month']))
       {
            $nbVentemois = Outil::getTotalvente($debut,  $fin );
            $nbproduitmois = Outil::getTotalproduitvente($debut,  $fin );
            $Caventemois= Outil::getCavente($debut,  $fin );
       }

       // ********ANNEE***********************

       if ( isset($args['date_year']))
       {
            $nbVenteannee = Outil::getTotalvente($debut,  $fin );
            $Caventeannee = Outil::getCavente($debut,  $fin );
            $nbproduitannee = Outil::getTotalproduitvente($debut,  $fin );
       }
        
       
        return
        [
            [
                'nb_vente_jour'           => json_encode($nbVenteJour),
                'nb_vente_mois'           => json_encode($nbVentemois),
                'nb_vente_annee'          => json_encode($nbVenteannee),
                'Caventeannee'            => $Caventeannee,
                'Caventejour'             => $Caventejour,
                'Caventemois'             => $Caventemois,
                'Caproduit'               => $caproduit,
                'nbproduitjour'           => json_encode($nbproduitjour),
                'nbproduitmois'           => json_encode($nbproduitmois),
                'nbproduitannee'          => json_encode($nbproduitannee),
            ]
        ];
    }

    
}
