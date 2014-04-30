<?php

return array(
    'router' => array(
        'routes' => array(
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
        ),
    ),
);