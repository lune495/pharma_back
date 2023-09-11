<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\User;
    
class UserQuery extends Query
{
    protected $attributes = [
        'name' => 'users'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('User'));
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
        $query = User::query();  
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }  
        $query = $query->get(); 
        return $query->map(function (User $item)
        {
            return
            [
                'id'                      => $item->id,
                'name'                    => $item->name,
                'email'                   => $item->email,
                'role_id'                 => $item->role_id,
                'role'                    => $item->role,
            ];
        });

    }
}
