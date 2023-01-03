<?php
namespace App\GraphQL\Type;

use App\Models\{Produit,Outil};
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Carbon\Carbon;
class VenteType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Vente',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'numero'                    => ['type' => Type::string()],
                'montant'                   => ['type' => Type::string()],
                'qte'                       => ['type' => Type::string()],
                'montantencaisse'           => ['type' => Type::string()],
                'monnaie'                   => ['type' => Type::string()],

                'user_id'                   => ['type' => Type::int()],
                'user'                      => ['type' => GraphQL::type('User')],
                'client'                    => ['type' => GraphQL::type('Client')],
                'taxe'                      => ['type' => GraphQL::type('Taxe')],
                'remise'                    => ['type' => GraphQL::type('Remise')],
                'vente_produits'            => ['type' => Type::listOf(GraphQL::type('VenteProduit')), 'description' => ''],
                'created_at'                => ['type' => Type::string()],
                'created_at'                => ['type' => Type::string()],
                'created_at_fr'             => ['type' => Type::string()],

            ];
    }

    /*************** Pour les dates ***************/
    protected function resolveCreatedAtField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $date_at = $root->created_at;
        }
        else
        {
            $date_at = is_string($root['created_at']) ? $root['created_at'] : $root['created_at']->format(Outil::formatdate());
        }
        return $date_at;
    }
    protected function resolveCreatedAtFrField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $created_at = $root->created_at;
        }
        else
        {
            $created_at = $root['created_at'];
        }
        return Carbon::parse($created_at)->format('d/m/Y H:i:s');
    }

    protected function resolveUpdatedAtField($root, $args)
    {
        if (!isset($root['updated_at']))
        {
            $date_at = $root->updated_at;
        }
        else
        {
            $date_at = is_string($root['updated_at']) ? $root['updated_at'] : $root['updated_at']->format(Outil::formatdate());
        }
        return $date_at;
    }   

    protected function resolveUpdatedAtFrField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $date_at = $root->created_at;
        }
        else
        {
            $date_at = $root['created_at'];
        }
        return Carbon::parse($date_at)->format('d/m/Y H:i:s');
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}