<?php

namespace BitWebTest\CronModule\Factory;

use BitWeb\CronModule\Configuration;
use BitWeb\CronModule\Factory\CronServiceFactory;
use BitWeb\CronModule\Service\CronService;
use Zend\ServiceManager\ServiceLocatorInterface;

class CronServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public $correctConfig = [];

    public function setUp()
    {
        $this->correctConfig = [
            'cronModule' => include __DIR__ . '/../TestAsset/config.php'
        ];
    }

    public function testCreateFactory()
    {
        $cronService = new CronService(new Configuration($this->correctConfig['cronModule']));
        $serviceLocatorInterfaceMock = $this->getMock(ServiceLocatorInterface::class);
        $serviceLocatorInterfaceMock->expects($this->any())->method('get')->will($this->returnValue($this->correctConfig));
        $factory = new CronServiceFactory();

        $this->assertEquals($cronService, $factory->createService($serviceLocatorInterfaceMock));
    }
} 