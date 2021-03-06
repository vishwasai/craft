<?php

use craft\console\Application;

return [
    'class' => Application::class,
    'bootstrap' => [
        'queue',
    ],
    'components' => [
        'errorHandler' => [
            'class' => craft\console\ErrorHandler::class,
        ],
        'request' => [
            'class' => craft\console\Request::class,
            'isConsoleRequest' => true,
        ],
        'user' => [
            'class' => craft\console\User::class,
        ],
    ],
    'controllerMap' => [
        'migrate' => craft\console\controllers\MigrateController::class,
    ],
    'controllerNamespace' => 'craft\\console\\controllers',
];
