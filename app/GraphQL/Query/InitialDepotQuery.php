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
            'nom_depot'           => ['type' => Type::string()],
            'search_produit'      => ['type' => Type::string()],
            'code_produit'      => ['type' => Type::string()],
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

        if (isset($args['search_produit'])) {
            $search = $args['search_produit'];

            $query->whereHas('depots.produit', function ($q) use ($search) {
                $q->where('designation', 'like', '%'.$search.'%');
            });

            // 🔹 Important : ici on restreint aussi les "depots" chargés dans le retour
            $query->with(['depots' => function ($d) use ($search) {
                $d->whereHas('produit', function ($p) use ($search) {
                    $p->where('designation', 'like', '%'.$search.'%');
                });
            }, 'depots.produit']);
            } else {
                // Si pas de filtre produit, charger tous les dépôts normalement
                $query->with('depots.produit');
        }
        // Recherche par code produit

        if (isset($args['code_produit'])) {
            $search = $args['code_produit'];

            $query->whereHas('depots.produit', function ($q) use ($search) {
                $q->where('code',$search);
            });

            // 🔹 Important : ici on restreint aussi les "depots" chargés dans le retour
            $query->with(['depots' => function ($d) use ($search) {
                $d->whereHas('produit', function ($p) use ($search) {
                    $p->where('code',$search);
                });
            }, 'depots.produit']);
            } else {
                // Si pas de filtre produit, charger tous les dépôts normalement
                $query->with('depots.produit');
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
