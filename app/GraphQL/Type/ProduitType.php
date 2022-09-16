<?php
namespace App\GraphQL\Type;

use App\Models\{Produit,Outil};
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
                'pa'                        => ['type' => Type::int()],
                'pv'                        => ['type' => Type::int()],
                'qte'                       => ['type' => Type::int()],
                'limite'                    => ['type' => Type::int()],

                'famille_id'                => ['type' => Type::int()],
                'famille'                   => ['type' => GraphQL::type('Famille')],
                'depots'                    => ['type' => Type::listOf(GraphQL::type('Depot')), 'description' => ''],
                'capital'                   => ['type' => Type::string()],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
    protected function resolveCapitalField($root, array $args)
    {
        $produits = Produit::all();
        $capital = 0;
        foreach ($produits as $produit)
        {
            $capital = $capital + ($produit->pa * $produit->qte);
        }
        $capital = Outil::formatPrixToMonetaire($capital, false, true);
        return $capital;
    }
}