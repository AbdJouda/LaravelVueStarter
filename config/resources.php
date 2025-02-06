<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Resource Class Mappings
    |---------------------------------------------------------------------------
    |
    | This configuration file is used to map models to their respective resource
    | classes for each API version. As the application grows, you may add or
    | modify mappings based on different versions or new resources.
    |
    */
    'App\Models\User' => [
        'v1' => 'App\Http\Resources\V1\UserResource',
//            'v2' => 'App\Http\Resources\V2\UserResource',
    ],
    'Illuminate\Notifications\DatabaseNotification' => [
        'v1' => 'App\Http\Resources\V1\NotificationResource',
    ],
    'App\Models\Role' => [
        'v1' => 'App\Http\Resources\V1\RoleResource',
    ],
    'App\Models\Permission' => [
        'v1' => 'App\Http\Resources\V1\RoleResource',
    ],
    'Illuminate\Pagination\LengthAwarePaginator' => [
        'v1' => 'App\Http\Resources\V1\PaginationResource',
    ],
    \App\Models\Todo::class => [
        'v1' => 'App\Http\Resources\V1\TodoResource',
    ],


];
