<?php
namespace App\GraphQL\Type;

use App\Models\InitialDepot;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class InitialDepotType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'InitialDepot',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'nom_depot'                 => ['type' => Type::string()],
                'depots'                    => ['type' => Type::listOf(GraphQL::type('Depot')), 'description' => ''],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}