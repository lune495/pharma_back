<?php

namespace App\GraphQL\Query;

use  App\Models\{LigneApprovisionnement,Outil};
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;


class LigneApprovisionnementsQuery extends Query
{
    protected $attributes =
    [
        'name' => 'ligneapprovisionnements'
    ];

    public function type():type
    {
        return Type::listOf(GraphQL::type('LigneApprovisionnement'));
    }


    public function args(): array
    {
        return
        [
            'id'                         => ['type' => Type::int()],
            'approvisionnement_id'       => ['type' => Type::int()],
            'produit_id'                 => ['type' => Type::int()]
        ];
    }

    public function resolve($root, $args)
    {
        $query = LigneApprovisionnement::with('approvisionnement');
        if (isset($args['id']))
        {
            $query->where('id', $args['id']);
        }
        if (isset($args['approvisionnement_id']))
        {
            $query->where('approvisionnement_id', $args['approvisionnement_id']);
        }

        if (isset($args['produit_id']))
        {
            $query->where('produit_id', $args['produit_id']);
        }
        $query->orderBy('produit_id','asc');
        $query = $query->get();

        return $query->map(function (LigneApprovisionnement $item)
        {
            return
            [
                'id'                                                              => $item->id,
                'approvisionnement_id'                                            => $item->approvisionnement_id,
                'produit_id'                                                      => $item->produit_id,
                'quantity_received'                                               => $item->quantity_received,
                'approvisionnement'                                               => $item->approvisionnement,
                'produit'                                                         => $item->produit,
                'created_at'                                                      => $item->created_at->format(Outil::formatdate()),
                'updated_at'                                                      => $item->updated_at->format(Outil::formatdate()),
            ];
        });

    }
}
