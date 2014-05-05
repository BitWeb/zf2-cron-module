<?php

return [
    'router' => [
        'routes' => [
            'cron-module-start' => [
                'type' => 'Simple',
                'options' => [
                    'route' => 'cron module start',
                    'defaults' => [
                        'controller' => 'BitWeb\CronModule\Controller\Index',
                        'action' => 'index'
                    ],
                ],
            ],
        ],
    ],
];