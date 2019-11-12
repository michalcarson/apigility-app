<?php

use DealerInspire\ApigilityApp\App;

return [
    'listeners' => [
        App::class,
    ],
    'service_manager' => [
        'invokables' => [
            App::class => App::class,
        ],
    ],
];
