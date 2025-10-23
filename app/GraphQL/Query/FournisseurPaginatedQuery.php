<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;
use \App\Models\Fournisseur;

class FournisseurPaginatedQuery extends Query
{
    protected $attributes = [
        'name'              => 'fournisseurspaginated',
        'description'       => ''
    ];

    public function type():type
    {
        return GraphQL::type('fournisseurspaginated');
    }

    public function args():array
    {
        return
        [
            'id'                            => ['type' => Type::int()],
            'nom_complet'                   => ['type' => Type::string()],
            'alias'                         => ['type' => Type::string()],
        
            'page'                          => ['name' => 'page', 'description' => 'The page', 'type' => Type::int() ],
            'count'                         => ['name' => 'count',  'description' => 'The count', 'type' => Type::int() ]
        ];
    }


    public function resolve($root, $args)
    {
        $query = Fournisseur::query();
        if (isset($args['nom_complet']))
        {
            $query = $query->where('nom_complet',Outil::getOperateurLikeDB(),'%'.$args['nom_complet'].'%');
        }
        if (isset($args['alias']))
        {
            $query = $query->where('alias',Outil::getOperateurLikeDB(),'%'.$args['alias'].'%');
        }
      
       $count = Arr::get($args, 'count', 20);
       $page  = Arr::get($args, 'page', 1);
       return $query->orderBy('nom_complet')->paginate($count, ['*'], 'page', $page);
    }
}

