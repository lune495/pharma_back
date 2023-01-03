<?php

namespace App\GraphQL\Query;

use  App\Models\{Remise};
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class RemiseQuery extends Query
{
    protected $attributes = [
        'name' => 'remises'
    ];

    public function type():type
    {
        return Type::listOf(GraphQL::type('Remise'));
    }

    public function args():array
    {
        return
        [
            'id'                       => ['type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Remise::query();
        if (isset($args['id']))
        {
            $query->where('id', $args['id']);
        }

        $query = $query->get();

        return $query->map(function (Remise $item)
        {
            return
            [
                'id'                                => $item->id,
                'nom'                               => $item->nom,
                'value'                             => $item->value,
            ];
        });
    }
}
