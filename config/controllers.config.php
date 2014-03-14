<?php

return array(
    'invokables' => array(
        'CronModule\Controller\Index' => 'CronModule\Controller\IndexController'
    ),
    'initializers' => array(
        function ($controller, \Zend\Mvc\Controller\ControllerManager $cm)
        {
            if ($controller instanceof \CronModule\Service\Cron\CronServiceAwareInterface) {
                $controller->setCronService($cm->getServiceLocator()->get('CronModule\Service\Cron'));
            }

            return $controller;
        }
    )
);