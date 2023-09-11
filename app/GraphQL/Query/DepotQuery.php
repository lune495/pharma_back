<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{Depot,Outil};
class DepotQuery extends Query
{
    protected $attributes = [
        'name' => 'depots'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Depot'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'produit_id'          => ['type' => Type::int()],
            'designation'         => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Depot::query();
        if (isset($args['produit_id']))
        {
            $query = $query->where('produit_id',$args['produit_id']);
        }
        if(isset($args['designation']))
        {
             $query = $query->join('produits', 'produits.id', '=', 'depots.produit_id')
            ->where('produits.designation',Outil::getOperateurLikeDB(),'%'.$args['designation'].'%')
            ->selectRaw('depots.*');
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
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
