<?php
namespace App\GraphQL\Type;

use App\Models\Produit;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class VenteType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Vente',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'montant'                   => ['type' => Type::string()],
                'qte'                       => ['type' => Type::string()],
                'montantencaisse'           => ['type' => Type::string()],
                'monnaie'                   => ['type' => Type::string()],

                'user_id'                   => ['type' => Type::int()],
                'user'                      => ['type' => GraphQL::type('User')]
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}