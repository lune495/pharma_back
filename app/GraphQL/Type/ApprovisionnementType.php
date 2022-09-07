<?php

namespace App\GraphQL\Type;

use  App\Models\{Approvisionnement,Outil,LigneApprovisionnement};
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\DB;

class ApprovisionnementType extends GraphQLType
{
    protected $attributes =
    [
        'name' => 'Approvisionnement',
        'description' => ''
    ];

    public function fields():array
    {
        return
        [
            'id'                                => ['type' => Type::int(), 'description' => ''],
            'user_id'                           => ['type' => Type::int(), 'description' => ''],

            'quantite'                          => ['type' => Type::int(), 'description' => ''],
            //'quantite_all'                      => ['type' => Type::int(), 'description' => ''],
            'fournisseur_id'                    => ['type' => Type::int()],

            'user'                              => ['type' => GraphQL::type('User'), 'description' => ''],
            'ligne_approvisionnements'          => ['type' => Type::listOf(GraphQL::type('LigneApprovisionnement')), 'description' => ''],
            'fournisseur'                       => ['type' => Type::listOf(GraphQL::type('Fournisseur')), 'description' => ''],



            'created_at'                        => [ 'type' => Type::string(), 'description' => ''],
            'created_at_fr'                     => [ 'type' => Type::string(), 'description' => ''],
            'updated_at'                        => [ 'type' => Type::string(), 'description' => ''],
            'updated_at_fr'                     => [ 'type' => Type::string(), 'description' => ''],
        ];
    }

    /*************** Pour les dates ***************/
    protected function resolveCreatedAtField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $date_at = $root->created_at;
        }
        else
        {
            $date_at = is_string($root['created_at']) ? $root['created_at'] : $root['created_at']->format(Outil::formatdate());
        }
        return $date_at;
    }
    protected function resolveCreatedAtFrField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $created_at = $root->created_at;
        }
        else
        {
            $created_at = $root['created_at'];
        }
        return Carbon::parse($created_at)->format('d/m/Y H:i:s');
    }

    protected function resolveUpdatedAtField($root, $args)
    {
        if (!isset($root['updated_at']))
        {
            $date_at = $root->updated_at;
        }
        else
        {
            $date_at = is_string($root['updated_at']) ? $root['updated_at'] : $root['updated_at']->format(Outil::formatdate());
        }
        return $date_at;
    }   

    protected function resolveUpdatedAtFrField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $date_at = $root->created_at;
        }
        else
        {
            $date_at = $root['created_at'];
        }
        return Carbon::parse($date_at)->format('d/m/Y H:i:s');
    }

  
    /*************** /Pour les dates ***************/
    protected function resolveQuantiteField($root, $args)
    {
        if (isset($root['quantite']))
        {
            $id = $root->id;
        }
        else
        {
            $id = $root['id'];
        }
        $ligneappro = LigneApprovisionnement::where('approvisionnement_id',$id)->get();
        $quantite=0;
        foreach($ligneappro as $oneligne){
            $quantite+=$oneligne->quantity_received;
        }

        return $quantite;
    }
    // protected function resolveQuantiteAllField($root, $args)
    // {
    //     if (isset($root['quantite_all']))
    //     {
    //         $id = $root->id;
    //     }
    //     else
    //     {
    //         $id = $root['id'];
    //     }
    //     $quantite= Approvisionnement::getAllqte();
    //     return $quantite;
    // }
}
