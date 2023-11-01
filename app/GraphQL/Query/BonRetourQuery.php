<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\BonRetour;
class BonRetourQuery extends Query
{
    protected $attributes = [
        'name' => 'bon_retours'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('BonRetour'));
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
        $query = BonRetour::with('ligne_bon_retours');

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
        return $query->map(function (BonRetour $item)
        {
            return
            [
                'id'                         => $item->id,
                'ref'                        => $item->ref,
                'nom_client'                 => $item->nom_client,
                'user'                       => $item->user,
                'ligne_bon_retours'          => $item->ligne_bon_retours,
                'created_at'                 => $item->created_at,
                'created_at_fr'              => $item->created_at_fr,
            ];
        });

    }
}