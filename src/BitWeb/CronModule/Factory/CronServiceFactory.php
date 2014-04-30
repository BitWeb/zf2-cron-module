<?php

namespace BitWeb\CronModule\Factory;

use BitWeb\CronModule\Configuration;
use BitWeb\CronModule\Service\CronService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CronServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $config = $config['cronModule'];

        return new CronService(new Configuration($config));
    }
}