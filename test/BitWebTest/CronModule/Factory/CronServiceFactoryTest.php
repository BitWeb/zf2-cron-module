<?php

namespace BitWebTest\CronModule\Factory;

use BitWeb\CronModule\Configuration;
use BitWeb\CronModule\Factory\CronServiceFactory;
use BitWeb\CronModule\Service\CronService;
use Zend\ServiceManager\ServiceLocatorInterface;

class CronServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public $correctConfig = [
        'cronModule' =>
            [
                'phpPath' => 'php',
                'scriptPath' => '/var/www/application/public/',
                'jobs' => [
                    [
                        'command' => 'index.php application cron mail',
                        'schedule' => '*/5 * * * *'
                    ]
                ],

                // timeout in seconds for the process, defaults to 600 seconds
                'timeout' => 850
            ]
    ];

    public function testCreateFactory()
    {

        $cronService = new CronService(new Configuration($this->correctConfig['cronModule']));
        $serviceLocatorInterfaceMock = $this->getMock(ServiceLocatorInterface::class);
        $serviceLocatorInterfaceMock->expects($this->any())->method('get')->will($this->returnValue($this->correctConfig));
        $factory = new CronServiceFactory();

        $this->assertEquals($cronService, $factory->createService($serviceLocatorInterfaceMock));
    }
} 