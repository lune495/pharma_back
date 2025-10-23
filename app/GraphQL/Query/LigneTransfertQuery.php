<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{LigneTransfert,User};
    
class LigneTransfertQuery extends Query
{
    protected $attributes = [
        'name' => 'ligne_transferts'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('LigneTransfert'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'role_id'             => ['type' => Type::int()],
        ];
    }   

    public function resolve($root, $args)
    {
        $query = LigneTransfert::query();  
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        $query->orderBy('id', 'desc');
        $query = $query->get(); 
        return $query->map(function (LigneTransfert $item)
        {
            return
            [
                'id'                      => $item->id,
                'produit'                 => $item->produit,
                'produit_id'              => $item->produit_id,
                'destination_depot_id'    => $item->destination_depot,
                'source_depot_id'         => $item->source_depot,
                'qte_transfere'           => $item->qte_transfere,
                'user_id'                 => $item->user_id,
                'user'                    => $item->user,
            ];
        });

    }
}
