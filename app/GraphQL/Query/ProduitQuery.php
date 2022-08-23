<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Produit;
class ProduitQuery extends Query
{
    protected $attributes = [
        'name' => 'produits'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Produit'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'nom'                 => ['type' => Type::string()],
            'code'                => ['type' => Type::string()],
            'designation'         => ['type' => Type::string(), 'description' => '']
        ];
    }

    public function resolve($root, $args)
    {
         $query = Produit::query();
       
        $query = $query->get();
        return $query->map(function (Produit $item)
        {
            return
            [
                'id'                      => $item->id,
                'code'                    => $item->code,
                'designation'             => $item->designation,
                'description'             => $item->description,
                'famille_id'              => $item->famille_id,
                'famille'                 => $item->famille,
            ];
        });

    }
}
