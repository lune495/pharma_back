<?php
namespace App\GraphQL\Type;

use App\Models\{LigneTransfert,Outil};
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class LigneTransfertType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'LigneTransfert',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'destination_depot_id'      => ['type' => Type::int()],
                'source_depot_id'           => ['type' => Type::int()],
                'nom_source_depot'          => ['type' => Type::string()],
                'nom_destination_depot'     => ['type' => Type::string()],
                'qte_transfere'             => ['type' => Type::int()],

                'produit_id'                => ['type' => Type::int()],
                'produit'                   => ['type' => GraphQL::type('Produit')],
                'user_id'                   => ['type' => Type::int()],
                'user'                      => ['type' => GraphQL::type('User')],
            ];
    }
                
    public function resolveNomSourceDepotField($root, $args)
    {
        return Outil::getNomDepotById($root['source_depot_id']);
    }

    public function resolveNomDestinationDepotField($root, $args)
    {
        return Outil::getNomDepotById($root['destination_depot_id']);
    }
}