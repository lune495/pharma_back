<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{Proforma,Outil};
class ProformaQuery extends Query
{
    protected $attributes = [
        'name' => 'proformas'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Proforma'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'client_id'           => ['type' => Type::int()],
            'user_id'             => ['type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Proforma::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['client_id']))
        {
            $query = $query->where('client_id', $args['client_id']);
        }
        if (isset($args['user_id']))
        {
            $query = $query->where('user_id', $args['user_id']);
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Proforma $item)
        {
            return
            [
                'id'                      => $item->id,
                'qte'                     => $item->qte,
                'client_id'               => $item->client_id,
                'client'                  => $item->client,
                'user_id'                 => $item->user_id,
                'user'                    => $item->user,
            ];
        });

    }
}
