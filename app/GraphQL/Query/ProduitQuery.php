<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Produit;
class ProduitQuery extends Query
{
    protected $attributes = [
        'name' => 'produits'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Produit'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'nom'                 => ['type' => Type::string()],
            'code'                => ['type' => Type::string()],
            'designation'         => ['type' => Type::string(), 'description' => '']
        ];
    }

    public function resolve($root, $args)
    {
         $query = Produit::query();
        // if(isset($args['id']))
        // {
        //     $query->where('id', $args['id']);
        // }
        // if(isset($args['nom']))
        // {
        //     $query = $query->where('nom',Outil::getOperateurLikeDB(),'%'.$args['nom'].'%');
        // }

        // if (isset($args['designation_en'])) {
        //     $query = $query->where('designation_en', Outil::getOperateurLikeDB(), '%' . $args['designation_en'] . '%');
        // }
        
        // if(isset($args['description']))
        // {
        //     $query->where('description',$args['description']);
        // }
        // if(isset($args['code_couleur']))
        // {
        //     $query->where('code_couleur',$args['code_couleur']);
        // }
        // if (isset($args['taille_id']))
        // {
        //     $query->whereIn('id', Declinaison::where('taille_id', $args['taille_id'])->get(['couleur_id']))->get();
        // }
        // if (isset($args['produit_id']))
        // {
        //     $query->whereIn('id', Declinaison::where('produit_id', $args['produit_id'])->get(['couleur_id']))->get();
        // }
       
        $query = $query->get();
        return $query->map(function (Produit $item)
        {
            return
            [
                'id'                      => $item->id,
                'code'                    => $item->code,
                'designation'             => $item->designation,
                'description'             => $item->description,
                'famille'                 => $item->famille,
            ];
        });

    }
}
