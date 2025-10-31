<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\TypeService;
class TypeServiceQuery extends Query
{
    protected $attributes = [
        'name' => 'type_services'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('TypeService'));
    }

    public function args(): array
    {
        return
        [
            'id'                    =>  ['type' => Type::int()],
            'nom'                   =>  ['type' => Type::string()],
            'module_id'             =>  ['type' => Type::int()],
            'bulletin_analyse'      =>  ['type' => Type::int()],
            'caisse'                =>  ['type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = TypeService::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['bulletin_analyse']))
        {
            $query = $query->where('bulletin_existe', false);
        }
        if (isset($args['nom'])) 
        {
            $query = $query->where('nom', 'like', '%' . $args['nom'] . '%');
        }
        if (isset($args['module_id'])) 
        {
            $query = $query->where('module_id', $args['module_id']);
        }
        if (isset($args['caisse'])) 
        {
            $query = $query->where('activer_type_service', true);
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (TypeService $item)
        {
            return
            [
                'id'                            => $item->id,
                'nom'                           => $item->nom,
                'prix'                          => $item->prix,
                'module_id'                     => $item->module_id,
                'bulletin_existe'               => $item->bulletin_existe,
                'module'                        => $item->module,
                'activer_type_service'          => $item->activer_type_service
            ];
        });
    }
}