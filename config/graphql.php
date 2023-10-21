<?php

declare(strict_types = 1);

return [
    'route' => [
        // The prefix for routes; do NOT use a leading slash!
        'prefix' => 'graphql',

        // The controller/method to use in GraphQL request.
        'controller' => \Rebing\GraphQL\GraphQLController::class . '@query',

        // Any middleware for the graphql route group
        // This middleware will apply to all schemas
        'middleware' => [],

        // Additional route group attributes
        //
        // Example:
        //
        // 'group_attributes' => ['guard' => 'api']
        //
        'group_attributes' => [],
    ],

    // The name of the default schema
    // Used when the route group is directly accessed
    'default_schema' => 'default',

    'batching' => [
        // Whether to support GraphQL batching or not.
        // See e.g. https://www.apollographql.com/blog/batching-client-graphql-queries-a685f5bcd41b/
        // for pro and con
        'enable' => true,
    ],

    // The schemas for query and/or mutation. It expects an array of schemas to provide
    // both the 'query' fields and the 'mutation' fields.
    //
    // You can also provide a middleware that will only apply to the given schema
    //
    // Example:
    //
    //  'schemas' => [
    //      'default' => [
    //          'controller' => MyController::class . '@method',
    //          'query' => [
    //              App\GraphQL\Queries\UsersQuery::class,
    //          ],
    //          'mutation' => [
    //
    //          ]
    //      ],
    //      'user' => [
    //          'query' => [
    //              App\GraphQL\Queries\ProfileQuery::class,
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //      'user/me' => [
    //          'query' => [
    //              App\GraphQL\Queries\MyProfileQuery::class,
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //  ]
    //
    'schemas' => [
        'default' => [
            'query' => [
                // ExampleQuery::class,
                \App\GraphQL\Query\ProduitQuery::class,
                \App\GraphQL\Query\InventaireQuery::class,
                \App\GraphQL\Query\InventairePaginatedQuery::class,
                \App\GraphQL\Query\SortieStockQuery::class,
                \App\GraphQL\Query\BonRetourQuery::class,
                \App\GraphQL\Query\SortieStockPaginatedQuery::class,
                \App\GraphQL\Query\BonRetourPaginatedQuery::class,
                \App\GraphQL\Query\ProformaQuery::class,
                \App\GraphQL\Query\ProduitPaginatedQuery::class,
                \App\GraphQL\Query\ProformaPaginatedQuery::class,
                \App\GraphQL\Query\TaxeQuery::class,
                \App\GraphQL\Query\RemiseQuery::class,
                \App\GraphQL\Query\FournisseurPaginatedQuery::class,
                \App\GraphQL\Query\DepotPaginatedQuery::class,
                \App\GraphQL\Query\ApprovisionnementPaginatedQuery::class,
                \App\GraphQL\Query\RolePaginatedQuery::class,
                \App\GraphQL\Query\VentePaginatedQuery::class,
                \App\GraphQL\Query\ClientPaginatedQuery::class,
                \App\GraphQL\Query\UserPaginatedQuery::class,
                \App\GraphQL\Query\FamilleQuery::class,
                \App\GraphQL\Query\UserQuery::class,
                \App\GraphQL\Query\VenteQuery::class,
                \App\GraphQL\Query\FournisseurQuery::class,
                \App\GraphQL\Query\DepotQuery::class,
                \App\GraphQL\Query\ClientQuery::class,
                \App\GraphQL\Query\ApprovisionnementsQuery::class,
                \App\GraphQL\Query\LigneApprovisionnementsQuery::class,
                \App\GraphQL\Query\VenteProduitsQuery::class,
                \App\GraphQL\Query\DashboardsQuery::class,
                \App\GraphQL\Query\RoleQuery::class,
                \App\GraphQL\Query\FamillePaginatedQuery::class,
            ],
            'mutation' => [
                // ExampleMutation::class,
            ],
            // The types only available in this schema
            'types' => [
                // ExampleType::class,

            ],

            // Laravel HTTP middleware
            'middleware' => ['web'],

            // Which HTTP methods to support; must be given in UPPERCASE!
            'method' => ['GET', 'POST'],

            // An array of middlewares, overrides the global ones
            'execution_middleware' => null,
        ],
    ],

    // The global types available to all schemas.
    // You can then access it from the facade like this: GraphQL::type('user')
    //
    // Example:
    //
    // 'types' => [
    //     App\GraphQL\Types\UserType::class
    // ]
    //
    'types' => [
        // ExampleType::class,
        // ExampleRelationType::class,
        // \Rebing\GraphQL\Support\UploadType::class,
        \App\GraphQL\Type\ProduitType::class,
        \App\GraphQL\Type\InventaireType::class,
        \App\GraphQL\Type\InventairePaginatedType::class,
        \App\GraphQL\Type\SortieStockType::class,
        \App\GraphQL\Type\BonRetourType::class,
        \App\GraphQL\Type\SortieStockPaginatedType::class,
        \App\GraphQL\Type\BonRetourPaginatedType::class,
        \App\GraphQL\Type\LigneSortieStockType::class,
        \App\GraphQL\Type\LigneInventaireType::class,
        \App\GraphQL\Type\LigneBonRetourType::class,
        \App\GraphQL\Type\ProformaType::class,
        \App\GraphQL\Type\TaxeType::class,
        \App\GraphQL\Type\RemiseType::class,
        \App\GraphQL\Type\ProduitPaginatedType::class,
        \App\GraphQL\Type\ProformaPaginatedType::class,
        \App\GraphQL\Type\ClientPaginatedType::class,
        \App\GraphQL\Type\DepotPaginatedType::class,
        \App\GraphQL\Type\UserPaginatedType::class,
        \App\GraphQL\Type\ApprovisionnementPaginatedType::class,
        \App\GraphQL\Type\RolePaginatedType::class,
        \App\GraphQL\Type\VentePaginatedType::class,
        \App\GraphQL\Type\FamilleType::class,
        \App\GraphQL\Type\VenteType::class,
        \App\GraphQL\Type\RoleType::class,
        \App\GraphQL\Type\ClientType::class,
        \App\GraphQL\Type\UserType::class,
        \App\GraphQL\Type\DepotType::class,
        \App\GraphQL\Type\ApprovisionnementType::class,
        \App\GraphQL\Type\ProformaProduitType::class,
        \App\GraphQL\Type\LigneApprovisionnementType::class,
        \App\GraphQL\Type\FournisseurPaginatedType::class,
        \App\GraphQL\Type\FournisseurType::class,
        \App\GraphQL\Type\DashboardType::class,
        \App\GraphQL\Type\VenteProduitType::class,
        \App\GraphQL\Type\ProformaProduitType::class,
        \App\GraphQL\Type\FamillePaginatedType::class,  
        \App\GraphQL\Type\Metadata::class,
    ],

    // The types will be loaded on demand. Default is to load all types on each request
    // Can increase performance on schemes with many types
    // Presupposes the config type key to match the type class name property
    'lazyload_types' => true,

    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    'error_formatter' => [\Rebing\GraphQL\GraphQL::class, 'formatError'],

    /*
     * Custom Error Handling
     *
     * Expected handler signature is: function (array $errors, callable $formatter): array
     *
     * The default handler will pass exceptions to laravel Error Handling mechanism
     */
    'errors_handler' => [\Rebing\GraphQL\GraphQL::class, 'handleErrors'],

    /*
     * Options to limit the query complexity and depth. See the doc
     * @ https://webonyx.github.io/graphql-php/security
     * for details. Disabled by default.
     */
    'security' => [
        'query_max_complexity' => null,
        'query_max_depth' => null,
        'disable_introspection' => false,
    ],

    /*
     * You can define your own pagination type.
     * Reference \Rebing\GraphQL\Support\PaginationType::class
     */
    'pagination_type' => \Rebing\GraphQL\Support\PaginationType::class,

    /*
     * You can define your own simple pagination type.
     * Reference \Rebing\GraphQL\Support\SimplePaginationType::class
     */
    'simple_pagination_type' => \Rebing\GraphQL\Support\SimplePaginationType::class,

    /*
     * Config for   QL (see (https://github.com/graphql/graphiql).
     */
    'graphiql' => [
        'prefix' => 'graphiql', // Do NOT use a leading slash
        'controller' => \Rebing\GraphQL\GraphQLController::class.'@graphiql',
        'middleware' => [],
        'view' => 'graphql::graphiql',
        'display' => env('ENABLE_GRAPHIQL', true),
    ],

    /*
     * Overrides the default field resolver
     * See http://webonyx.github.io/graphql-php/data-fetching/#default-field-resolver
     *
     * Example:
     *
     * ```php
     * 'defaultFieldResolver' => function ($root, $args, $context, $info) {
     * },
     * ```
     * or
     * ```php
     * 'defaultFieldResolver' => [SomeKlass::class, 'someMethod'],
     * ```
     */
    'defaultFieldResolver' => null,

    /*
     * Any headers that will be added to the response returned by the default controller
     */
    'headers' => [],

    /*
     * Any JSON encoding options when returning a response from the default controller
     * See http://php.net/manual/function.json-encode.php for the full list of options
     */
    'json_encoding_options' => 0,

    /*
     * Automatic Persisted Queries (APQ)
     * See https://www.apollographql.com/docs/apollo-server/performance/apq/
     *
     * Note 1: this requires the `AutomaticPersistedQueriesMiddleware` being enabled
     *
     * Note 2: even if APQ is disabled per configuration and, according to the "APQ specs" (see above),
     *         to return a correct response in case it's not enabled, the middleware needs to be active.
     *         Of course if you know you do not have a need for APQ, feel free to remove the middleware completely.
     */
    'apq' => [
        // Enable/Disable APQ - See https://www.apollographql.com/docs/apollo-server/performance/apq/#disabling-apq
        'enable' => env('GRAPHQL_APQ_ENABLE', false),

        // The cache driver used for APQ
        'cache_driver' => env('GRAPHQL_APQ_CACHE_DRIVER', config('cache.default')),

        // The cache prefix
        'cache_prefix' => config('cache.prefix') . ':graphql.apq',

        // The cache ttl in seconds - See https://www.apollographql.com/docs/apollo-server/performance/apq/#adjusting-cache-time-to-live-ttl
        'cache_ttl' => 300,
    ],

    /*
     * Execution middlewares
     */
    'execution_middleware' => [
        \Rebing\GraphQL\Support\ExecutionMiddleware\ValidateOperationParamsMiddleware::class,
        // AutomaticPersistedQueriesMiddleware listed even if APQ is disabled, see the docs for the `'apq'` configuration
        \Rebing\GraphQL\Support\ExecutionMiddleware\AutomaticPersistedQueriesMiddleware::class,
        \Rebing\GraphQL\Support\ExecutionMiddleware\AddAuthUserContextValueMiddleware::class,
        // \Rebing\GraphQL\Support\ExecutionMiddleware\UnusedVariablesMiddleware::class,
    ],
];
