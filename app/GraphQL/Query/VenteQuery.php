<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Vente;
class VenteQuery extends Query
{
    protected $attributes = [
        'name' => 'ventes'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Vente'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'user_id'             => ['type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
         $query = Vente::query();
       
        $query = $query->get();
        return $query->map(function (Vente $item)
        {
            return
            [
                'id'                      => $item->id,
                'qte'                     => $item->qte,
                'montantencaisse'         => $item->montantencaisse,
                'monnaie'                 => $item->monnaie,
                'user_id'                 => $item->user_id,
                'user'                    => $item->user,
            ];
        });

    }
}
