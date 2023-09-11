<?php
namespace App\GraphQL\Type;

use App\Models\Depot;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class DepotType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Depot',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'produit_id'                => ['type' => Type::int()],
                'produit'                   => ['type' => GraphQL::type('Produit')],
                'stock'                     => ['type' => Type::int()],
                'pa'                        => ['type' => Type::int()],
                'limite'                     => ['type' => Type::int()]
            ];
    }
}