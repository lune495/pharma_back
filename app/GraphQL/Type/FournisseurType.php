<?php
namespace App\GraphQL\Type;

use App\Models\Fournisseur;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FournisseurType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Fournisseur',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'nom_complet'               => ['type' => Type::string()],
                'email'                     => ['type' => Type::string()],
                'telephone'                 => ['type' => Type::string()],
                'adresse'                   => ['type' => Type::string()],
                'alias'                     => ['type' => Type::string()],
                'image'                     => ['type' => Type::string()],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}