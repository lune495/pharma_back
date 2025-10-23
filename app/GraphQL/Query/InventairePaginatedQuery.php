<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;
use \App\Models\Inventaire;

class InventairePaginatedQuery extends Query
{
    protected $attributes = [
        'name'              => 'inventairespaginated',
        'description'       => ''
    ];

    public function type():type
    {
        return GraphQL::type('inventairespaginated');
    }

    public function args():array
    {
        return
        [
            'id'                            => ['type' => Type::int()],
            'ref'                           => ['type' => Type::string()],
            'user_id'                       => ['type' => Type::int()],
        
            'page'                          => ['name' => 'page', 'description' => 'The page', 'type' => Type::int() ],
            'count'                         => ['name' => 'count',  'description' => 'The count', 'type' => Type::int() ]
        ];
    }


    public function resolve($root, $args)
    {
        $query = Inventaire::query();
        if (isset($args['ref']))
        {
            $query = $query->where('ref',Outil::getOperateurLikeDB(),'%'.$args['ref'].'%');
        }
        if (isset($args['user_id']))
        {
            $query = $query->where('user_id',$args['user_id']);
        }
      
       $count = Arr::get($args, 'count', 20);
       $page  = Arr::get($args, 'page', 1);
       return $query->orderBy('created_at', 'desc')->paginate($count, ['*'], 'page', $page);
    }
}

