<?php
namespace App\GraphQL\Type;

use App\Models\{Proforma,Outil};
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProformaType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Proforma',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'qte'                       => ['type' => Type::int()],
                'client_id'                 => ['type' => Type::int()],
                'client'                    => ['type' => GraphQL::type('Client')],
                'user_id'                   => ['type' => Type::int()],
                'user'                      => ['type' => GraphQL::type('User')]
            ];
    }
    protected function resolveQteField($root, array $args)
    {
        
        return $root['qte']==null ? 0 : $root['qte'];
    }
}