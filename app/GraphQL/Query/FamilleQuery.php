<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Famille;
    
class FamilleQuery extends Query
{
    protected $attributes = [
        'name' => 'familles'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Famille'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'nom'                 => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Famille::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['nom']))
        {
            $query = $query->where('nom', 'like', '%'.$args['nom'].'%');
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Famille $item)
        {
            return
            [
                'id'                      => $item->id,
                'nom'                     => $item->nom,
            ];
        });

    }
}
