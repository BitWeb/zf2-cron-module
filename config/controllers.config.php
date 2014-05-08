<?php

return [
    'invokables' => [
        'BitWeb\CronModule\Controller\Index' => 'BitWeb\CronModule\Controller\IndexController'
    ],
    'initializers' => [
        function ($controller, \Zend\Mvc\Controller\ControllerManager $cm) {
            if ($controller instanceof \BitWeb\CronModule\Service\Cron\CronServiceAwareInterface) {
                $controller->setCronService($cm->getServiceLocator()->get('BitWeb\CronModule\Service\Cron'));
            }

            return $controller;
        }
    ]
];