<?php

// For PHP <= 5.4, you should replace any ::class references with strings
// remove the first \ and the ::class part and encase in single quotes

return [
    'bjyauthorize' => [

        // set the 'guest' role as default (must be defined in a role provider)
        'default_role' => 'guest',

        'identity_provider' => \BjyAuthorize\Provider\Identity\ZfcUserZendDb::class,

        'role_providers' => [

            // this will load roles from the user_role table in a database
            // format: user_role(role_id(varchar], parent(varchar))
            \BjyAuthorize\Provider\Role\ZendDb::class => [
                'table'                 => 'user_role',
                'identifier_field_name' => 'id',
                'role_id_field'         => 'roleId',
                'parent_role_field'     => 'parent_id',
            ],


        ],

        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'hidden_object' => array(),
            ),
        ),

        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array( array( 'admin' ), 'hidden_object', array( 'admin' ) ), //Show object only to admin
                    array( array( 'admin','user' ), 'hidden_object', array( 'user' ) ), //Show object to user and admin
                    array( array( 'admin','user','guest' ), 'hidden_object', array( 'guest' )), //Show object to guest, user, and admin
                ),
            ),
        ),

        'guards' => [
            \BjyAuthorize\Guard\Route::class => [
                ['route' => 'zfcuser', 'roles' => ['user', 'admin']],
                ['route' => 'zfcuser/login', 'roles' => ['guest', 'user', 'admin']],
                ['route' => 'zfcuser/logout', 'roles' => ['guest', 'user', 'admin']],
                ['route' => 'zfcuser/register', 'roles' => ['guest', 'user', 'admin']],
                ['route' => 'home', 'roles' => ['guest', 'user','admin']],
                ['route' => 'about', 'roles' => ['guest', 'user','admin']],
                ['route' => 'blog', 'roles' => ['guest', 'user', 'admin']],
            ],
        ],
    ],
];