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
