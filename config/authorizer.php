<?php

return [
    'pivots' => [
        'user_class' => 'App\Models\User',
        'permissions' => [
            'verbs' => ['viewAny' => "View any", 'view' => "View", 'update' => "Update", 'delete' => "Delete", 'forceDelete' => "Force delete"],
        ],
    ],
];