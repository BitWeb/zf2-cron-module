<?php

namespace BitWeb\CronModule;

use Zend\Console\Adapter\AdapterInterface;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ],
        ];
    }

    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            'cron module start' => 'Run cron processes.',
        ];
    }
}
