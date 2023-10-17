<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\SortieStock;
class SortieStockQuery extends Query
{
    protected $attributes = [
        'name' => 'sortiestocks'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('SortieStock'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'ref'                 => ['type' => Type::string()],
            'user_id'             => ['type' => Type::int()]
        ];
    }

    public function resolve($root, $args)
    {
        $query = SortieStock::with('ligne_sortie_stocks');

        if (isset($args['id']))
        {
            $query->where('id', $args['id']);
        }
        if (isset($args['ref']))
        {
            $query->where('ref',Outil::getOperateurLikeDB(),'%'.$args['ref'].'%');
        }

        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (SortieStock $item)
        {
            return
            [
                'id'                         => $item->id,
                'ref'                        => $item->ref,
                'user'                       => $item->user,
                'ligne_sortie_stocks'        => $item->ligne_sortie_stocks,
                'created_at'                 => $item->created_at,
                'created_at_fr'              => $item->created_at_fr,
            ];
        });

    }
}
