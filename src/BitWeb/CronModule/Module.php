<?php

namespace BitWeb\CronModule;

use Zend\Console\Adapter\AdapterInterface;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        $dir = dirname(dirname(dirname(__DIR__)));
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => $dir . '/src/' . __NAMESPACE__,
                ],
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
