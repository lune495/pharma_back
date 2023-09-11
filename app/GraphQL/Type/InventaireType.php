<?php
namespace App\GraphQL\Type;

use App\Models\{Inventaire,Outil,LigneInventaire};
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Carbon\Carbon;
class InventaireType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Inventaire',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'ref'                       => ['type' => Type::string()],
                'user'                      => ['type' => GraphQL::type('User')],
                'ligne_inventaires'         => ['type' => Type::listOf(GraphQL::type('LigneInventaire')), 'description' => ''],
                'created_at'                => ['type' => Type::string()],
                'created_at_fr'             => ['type' => Type::string()],
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

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}