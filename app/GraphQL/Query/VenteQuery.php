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
            'produit_id'          => ['type' => Type::int()],
            'created_at_start'    => ['type' => Type::string()],
            'created_at_end'      => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Vente::with('vente_produits');

        if (isset($args['id']))
        {
            $query->where('id', $args['id']);
        }
        if(isset($args['produit_id']))
        {
            $query->whereIn('id', VenteProduit::where('produit_id', $args['produit_id'])->get(['vente_id']))->get();
        }
        if (isset($args['created_at_start']) && isset($args['created_at_end']))
        {
            $from = $args['created_at_start'];
            $to = $args['created_at_end'];

            // Eventuellement la date fr
            $from = (strpos($from, '/') !== false) ? Carbon::createFromFormat('d/m/Y', $from)->format('Y-m-d') : $from;
            $to = (strpos($to, '/') !== false) ? Carbon::createFromFormat('d/m/Y', $to)->format('Y-m-d') : $to;

            $from = date($from.' 00:00:00');
            $to = date($to.' 23:59:59');
            $query->whereBetween('created_at', array($from, $to));
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Vente $item)
        {
            return
            [
                'id'                      => $item->id,
                'qte'                     => $item->qte,
                'montant'                 => $item->montant,
                'montantencaisse'         => $item->montantencaisse,
                'monnaie'                 => $item->monnaie,
                'user_id'                 => $item->user_id,
                'user'                    => $item->user,
                'client'                  => $item->client,
                'vente_produits'          => $item->vente_produits,
                'created_at'              => $item->created_at,
            ];
        });

    }
}
