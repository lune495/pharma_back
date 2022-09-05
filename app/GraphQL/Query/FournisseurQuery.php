<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Fournisseur;
    
class FournisseurQuery extends Query
{
    protected $attributes = [
        'name' => 'fournisseurs'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Fournisseur'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'nom_complet'         => ['type' => Type::string()],
            'alias'               => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Fournisseur::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['nom_complet']))
        {
            $query = $query->where('nom_complet', 'like', '%'.$args['nom_complet'].'%');
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Fournisseur $item)
        {
            return
            [
                'id'                        => $item->id,
                'nom_complet'               => $item->nom_complet,
                'email'                     => $item->email,
                'adresse'                   => $item->adresse,
                'alias'                     => $item->alias,
                'image'                     => $item->image,
                'telephone'                 => $item->telephone,
            ];
        });

    }
}
