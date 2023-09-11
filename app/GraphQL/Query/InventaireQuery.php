<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Inventaire;
class InventaireQuery extends Query
{
    protected $attributes = [
        'name' => 'inventaires'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Inventaire'));
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
        $query = Inventaire::with('ligne_inventaires');

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
        return $query->map(function (Inventaire $item)
        {
            return
            [
                'id'                      => $item->id,
                'ref'                     => $item->ref,
                'ligne_inventaires'       => $item->ligne_inventaires,
                'created_at'              => $item->created_at,
                'created_at_fr'           => $item->created_at_fr,
            ];
        });

    }
}
