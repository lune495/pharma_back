<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\InitialDepot;
    
class InitialDepotQuery extends Query
{
    protected $attributes = [
        'name' => 'initial_depots'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('InitialDepot'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'nom_depot'           => ['type' => Type::string()]
        ];
    }

    public function resolve($root, $args)
    {
        $query = InitialDepot::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['nom_depot']))
        {
            $query = $query->where('nom_depot', 'like', '%'.$args['nom_depot'].'%');
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (InitialDepot $item)
        {
            return
            [
                'id'                        => $item->id,
                'nom_depot'                 => $item->nom_depot,
                'depots'                    => $item->depots
            ];
        });

    }
}
