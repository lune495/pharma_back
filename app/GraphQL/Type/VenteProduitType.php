<?php

namespace App\GraphQL\Type;

use App\Models\VenteProduit;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class VenteProduitType extends GraphQLType
{

    protected $attributes =
    [
        'name' => 'VenteProduit',
        'description' => ''
    ];

    public function fields():array
    {
        return
        [
            'id'                                                        => ['type' => Type::int(), 'description' => ''],
            'vente_id'                                                  => ['type' => Type::int(), 'description' => ''],
            'produit_id'                                                => ['type' => Type::int(), 'description' => ''],
            'qte'                                                       => ['type' => Type::int(), 'description' => ''],
            'vente'                                                     => ['type' => GraphQL::type('Vente'), 'description' => ''],
            'total'                                                     => ['type' => Type::int(), 'description' => ''],
            'produit'                                                   => ['type' => GraphQL::type('Produit'), 'description' => ''],

            'created_at'                                                => ['type' => Type::string(), 'description' => ''],
            'updated_at'                                                => ['type' => Type::string(), 'description' => ''],
            'deleted_at'                                                => ['type' => Type::string(), 'description' => ''],
        ];
    }


    protected function resolveTotalField($root, $args)
    {
        if (!isset($root['total']))
        {
            $id = $root->id;
        }
        else
        {
            $id = $root['id'];
        }

        return VenteProduit::getTotal($id);
    }
    
}



