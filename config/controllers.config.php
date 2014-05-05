<?php

return [
    'invokables' => [
        'CronModule\Controller\Index' => 'CronModule\Controller\IndexController'
    ],
    'initializers' => [
        function ($controller, \Zend\Mvc\Controller\ControllerManager $cm) {
            if ($controller instanceof \BitWeb\CronModule\Service\Cron\CronServiceAwareInterface) {
                $controller->setCronService($cm->getServiceLocator()->get('CronModule\Service\Cron'));
            }

            return $controller;
        }
    ]
];