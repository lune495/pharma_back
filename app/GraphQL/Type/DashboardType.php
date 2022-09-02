<?php

namespace App\GraphQL\Type;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{Vente,Outil};



class DashboardType extends GraphQLType
{

    protected $attributes = [
        'name' => 'Dashboard',
        'description' => ''
    ];

    public function fields():array
    {
        return
        [
            
            'nb_vente_jour'              => ['type' => Type::int(), 'description' => ''],
            'nb_vente_mois'              => ['type' => Type::int(), 'description' => ''],
            'nb_vente_annee'             => ['type' => Type::int(), 'description' => ''],

            'nbproduitjour'              => ['type' => Type::int(), 'description' => ''],
            'nbproduitmois'              => ['type' => Type::int(), 'description' => ''],
            'nbproduitannee'             => ['type' => Type::int(), 'description' => ''],
            'Caproduit'                  => ['type' => Type::int(), 'description' => ''],

            // 'nbdepensejour'              => ['type' => Type::int(), 'description' => ''],
            // 'nbdepensemois'              => ['type' => Type::int(), 'description' => ''],
            // 'nbdepenseannee'             => ['type' => Type::int(), 'description' => ''],

            'Caventejour'              => ['type' => Type::int(), 'description' => ''],
            'Caventemois'              => ['type' => Type::int(), 'description' => ''],
            'Caventeannee'             => ['type' => Type::int(), 'description' => ''],
            // 'nombrenouveauclientjour'             => ['type' => Type::int(), 'description' => ''],
            // 'nombrenouveauclientmois'             => ['type' => Type::int(), 'description' => ''],
            // 'nombrenouveauclientannee'             => ['type' => Type::int(), 'description' => ''],

            // 'montantdepensejour'             => ['type' => Type::int(), 'description' => ''],
            // 'montantdepensemois'             => ['type' => Type::int(), 'description' => ''],
            // 'montantdepenseannee'             => ['type' => Type::int(), 'description' => ''],
            
            
            // 'paniermoyenjour'             => ['type' => Type::int(), 'description' => ''],
            // 'paniermoyenmois'             => ['type' => Type::int(), 'description' => ''],
            // 'paniermoyenannee'             => ['type' => Type::int(), 'description' => ''],

            'prctjour'                    => ['type' => Type::int(), 'description' => ''],
            'prctmois'                    => ['type' => Type::int(), 'description' => ''],
            'prctannee'                   => ['type' => Type::int(), 'description' => ''],
            
            'resultatnetjour'             => ['type' => Type::int(), 'description' => ''],
            'resultatnetmois'             => ['type' => Type::int(), 'description' => ''],
            'resultatnetannee'            => ['type' => Type::int(), 'description' => ''],

            // 'montantdepot_a_verserjour'             => ['type' => Type::int(), 'description' => ''],

            // 'montantdepot_a_versermois'             => ['type' => Type::int(), 'description' => ''],

            // 'montantdepot_a_verserannee'             => ['type' => Type::int(), 'description' => ''],

           // get_mnt_depot_vente_reverse
           
            'beneficenetjour'                   => ['type' => Type::int(), 'description' => ''],
            'beneficenetmois'                  => ['type' => Type::int(), 'description' => ''],
            'beneficenetannee'                 => ['type' => Type::int(), 'description' => ''],

            'couttotal_appro_jour'             => ['type' => Type::int(), 'description' => ''],
            'couttotal_appro_mois'             => ['type' => Type::int(), 'description' => ''],
            'couttotal_appro_annee'             => ['type' => Type::int(), 'description' => ''],

            // 'meilleurs_clients'             => ['type' => Type::string(), 'description' => ''],

        ];
    }

    // protected function resolveMeilleursClientsField($root, $args)
    // { 
    //     $retour =  Outil::donneMeilleursClients();
    //     return $retour;
    // }
   
}
