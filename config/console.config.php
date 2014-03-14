<?php

return array(
    'router' => array(
        'routes' => array(
            'cron-module-start' => array(
                'type' => 'Simple',
                'options' => array(
                    'route' => 'cron module start',
                    'defaults' => array(
                        'controller' => 'CronModule\Controller\Index',
                        'action' => 'index'
                    ),
                ),
            ),
        ),
    ),
);