<?php
namespace App\GraphQL\Type;

use App\Models\Medecin;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Carbon\Carbon;

class MedecinType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Medecin',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'nom'                       => ['type' => Type::string()],
                'prenom'                    => ['type' => Type::string()],
                'module'                    => ['type' => GraphQL::type('Module')],
                'plannings'                 => ['type' => Type::listOf(GraphQL::type('Planning')), 'description' => ''],
                'services'                  => ['type' => Type::listOf(Type::string())]
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    protected function resolveServicesField($root, array $args)
    {
        return $root->plannings->pluck('date_planning')->unique()->map(function ($date) 
                        {  
                            return Carbon::parse($date)->locale('fr')->translatedFormat('l');
                        })->values()->toArray();
    }
}