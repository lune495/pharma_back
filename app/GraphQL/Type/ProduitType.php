<?php
namespace App\GraphQL\Type;

use App\Models\Produit;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProduitType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Produit',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'code'                      => ['type' => Type::string()],
                'designation'               => ['type' => Type::string()],
                'description'               => ['type' => Type::string()],
                'pa'                        => ['type' => Type::string()],
                'pv'                        => ['type' => Type::string()],
                'qte'                       => ['type' => Type::string()],

                'famille_id'                => ['type' => Type::int()],
                'famille'                   => ['type' => GraphQL::type('Famille')],
                'depots'                    => ['type' => Type::listOf(GraphQL::type('Depot')), 'description' => '']
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}