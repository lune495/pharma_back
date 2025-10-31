<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{InitialDepot,Outil};
    
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

        // --- 🔍 Filtrage de base ---
        if (isset($args['id'])) {
            $query->where('id', $args['id']);
        }

        if (isset($args['nom_depot'])) {
            $query->where('nom_depot', 'like', '%'.$args['nom_depot'].'%');
        }

        // --- 🔎 Recherche par produit ---
        if (!empty($args['search_produit']) || !empty($args['code_produit'])) {
            $search = $args['search_produit'] ?? null;
            $code   = $args['code_produit'] ?? null;

            // Condition commune pour filtrer les dépôts ayant un produit correspondant
            $query->whereHas('depots.produit', function ($q) use ($search, $code) {
                if ($search) {
                    $q->where('designation', Outil::getOperateurLikeDB(), '%'.$search.'%');
                }
                if ($code) {
                    $q->where('code', $code);
                }
            });

            // Charger seulement les dépôts filtrés
            $query->with(['depots' => function ($d) use ($search, $code) {
                $d->whereHas('produit', function ($p) use ($search, $code) {
                    if ($search) {
                        $p->where('designation', Outil::getOperateurLikeDB(), '%'.$search.'%');
                    }
                    if ($code) {
                        $p->where('code', $code);
                    }
                })->with('produit');
            }]);
        } else {
            // Aucun filtre produit, charger tous les produits liés
            $query->with('depots.produit');
        }

        $query->orderByDesc('id');

        return $query->get()->map(function (InitialDepot $item) {
            return [
                'id'        => $item->id,
                'nom_depot' => $item->nom_depot,
                'depots'    => $item->depots,
            ];
        });
    }

}
