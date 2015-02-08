<?php

namespace BitWebTest\CronModule;

use BitWeb\CronModule\Module;
use Zend\Console\Adapter\Posix;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreate()
    {
        $this->assertInstanceOf(Module::class, new Module());
    }

    public function testGetConfig()
    {
        $module = new Module();
        $this->assertEquals(include __DIR__ . '/../../../config/module.config.php', $module->getConfig());
    }

    public function testGetAutoloaderConfig()
    {
        $dir = dirname(dirname(dirname(__DIR__)));

        $target = [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    'BitWeb\\CronModule' => $dir . '/src/BitWeb\\CronModule',
                ],
            ],
        ];

        $module = new Module();
        $this->assertEquals($target, $module->getAutoloaderConfig());
    }

    public function testGetConsoleUsage()
    {
        $module = new Module();
        $this->assertTrue(is_array($module->getConsoleUsage(new Posix())));
    }

    public function testGetControllerConfig()
    {
        $module = new Module();
        $config = $module->getControllerConfig();
        $this->assertEquals(['BitWeb\CronModule\Controller\Index' => 'BitWeb\CronModule\Controller\IndexController'], $config['invokables']);
        $this->assertTrue(is_array($config['initializers']));
        $this->assertInstanceOf('Closure', $config['initializers'][0]);
    }
}
 