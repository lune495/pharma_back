<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;
use \App\Models\Depot;

class DepotPaginatedQuery extends Query
{
    protected $attributes = [
        'name'              => 'depotspaginated',
        'description'       => ''
    ];

    public function type():type
    {
        return GraphQL::type('depotspaginated');
    }

    public function args():array
    {
        return
        [
            'id'                            => ['type' => Type::int()],
            'produit_id'                    => ['type' => Type::int()],
            'page'                          => ['name' => 'page', 'description' => 'The page', 'type' => Type::int() ],
            'count'                         => ['name' => 'count',  'description' => 'The count', 'type' => Type::int() ]
        ];
    }


    public function resolve($root, $args)
    {
        $query = Depot::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['produit_id']))
        {
            $query = $query->where('produit_id',$args['produit_id']);
        }
      
       $count = Arr::get($args, 'count', 20);
       $page  = Arr::get($args, 'page', 1);
       return $query->orderBy('id')->paginate($count, ['*'], 'page', $page);
       $query->get();
        return $query->map(function (Depot $item)
        {
            return
            [
                'id'                      => $item->id,
                'stock'                   => $item->stock,
                'pa'                      => $item->pa,
                'limite'                  => $item->limite,
                'produit_id'              => $item->produit_id,
                'produit'                 => $item->produit,
            ];
        });
    }
}

