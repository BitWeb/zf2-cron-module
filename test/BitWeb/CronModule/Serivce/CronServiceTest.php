<?php
/**
 * Created by PhpStorm.
 * User: kristjan
 * Date: 4/28/14
 * Time: 4:21 PM
 */

namespace BitWeb\CronModule\Service;

use BitWeb\CronModule\Configuration;
use BitWeb\CronModule\Executor\Executor;
use Cron\Cron;
use Cron\Job\ShellJob;
use Cron\Resolver\ArrayResolver;
use Cron\Schedule\CrontabSchedule;
use Cron\Executor\Executor as CronExecutor;

class CronServiceTest extends \PHPUnit_Framework_TestCase {

    public $configuration;

    public $correctConfig = [
        'phpPath' => 'php',
        'scriptPath' => '/var/www/application/public/',
        'jobs' => [
            [
                'command' => 'index.php application cron mail',
                'schedule' => '*/5 * * * *'
            ]
        ],

        // timeout in seconds for the process, defaults to 600 seconds
        'timeout' => 2
    ];


    protected static function getMethod($class, $name)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testCanCreate()
    {
        $this->assertInstanceOf(CronService::class, new CronService(new Configuration($this->correctConfig)));
    }

    public function testGetResolverInstance()
    {
        $this->assertInstanceOf(ArrayResolver::class, (new CronService(new Configuration($this->correctConfig)))->getResolver());
    }

    public function testGetExecutorInstance()
    {
        $service = new CronService(new Configuration($this->correctConfig));
        $method = self::getMethod(CronService::class, 'getExecutor');
        $this->assertInstanceOf(Executor::class, $method->invokeArgs($service, array()));
    }

    public function testInitCron()
    {
        $service = new CronService(new Configuration($this->correctConfig));
        $method = self::getMethod(CronService::class, 'initCron');
        $method->invokeArgs($service, array());
        $this->assertInstanceOf(Cron::class, \PHPUnit_Framework_Assert::readAttribute($service, 'cron'));
        $this->assertInstanceOf(Executor::class, \PHPUnit_Framework_Assert::readAttribute($service, 'executor'));
        $this->assertInstanceOf(ArrayResolver::class, \PHPUnit_Framework_Assert::readAttribute($service, 'resolver'));
    }

    public function testRun()
    {
        $this->configuration = new Configuration($this->correctConfig);
        $jobs = array();
        foreach ($this->configuration->getJobs() as $jobArray) {
            $job = new ShellJob();
            $job->setCommand($this->assembleShellJobString($jobArray['command']));
            $job->setSchedule(new CrontabSchedule($jobArray['schedule']));

            $jobs[] = $job;
        }

        $cronMock = $this->getMock(Cron::class);
        $cronMock->expects($this->once())->method('run')->will($this->returnValue(null));
        $service = new CronService($this->configuration);
        $service->setCron($cronMock);
        $service->run();
        $serviceResolver = \PHPUnit_Framework_Assert::readAttribute($service, 'resolver');
        $serviceExecutor = \PHPUnit_Framework_Assert::readAttribute($service, 'executor');
        $resolverJobs = \PHPUnit_Framework_Assert::readAttribute($serviceResolver, 'jobs');

        $this->assertEquals($jobs, $resolverJobs);


    }

    /**
     * @expectedException \BitWeb\CronModule\Exception\TimeoutException
     */
    public function testTimeOut()
    {
        $job = new ShellJob();
        $job->setCommand('index.php application cron mail');
        $this->configuration = new Configuration($this->correctConfig);
        $callback = function($time) {
            sleep($time);
        };
        $executorMock = $this->getMock(Executor::class);
        $executorMock->expects($this->any())->method('isRunning')->will($this->returnValue(true));
        $executorMock->expects($this->any())->method('getRunningJobs')->will($this->returnValue(array($job)));
        $service = new CronService($this->configuration);
        $service->setExecutor($executorMock);
        $service->run();
        sleep(3);
    }

//    public function testRun()
//    {
//        $this->configuration = new Configuration($this->correctConfig);
//        $executorMock = $this->getMock(Executor::class);
//        $cronMock = $this->getMock(CronExecutor::class);
//        $serviceMock = $this->getMock(CronService::class, array(), array(new Configuration($this->correctConfig)));
//        $method = self::getMethod(CronService::class, 'assembleShellJobString');
//        $method2 = self::getMethod(Executor::class, 'getRunningJobs');
//        $method3 = self::getMethod(Executor::class, 'prepareSets');
//
//        $jobs = array();
//        foreach ($this->configuration->getJobs() as $jobArray) {
//            $job = new ShellJob();
//            $job->setCommand($this->assembleShellJobString($jobArray['command']));
//            $job->setSchedule(new CrontabSchedule($jobArray['schedule']));
//
//            $jobs[] = $job;
//        }
//
//        $executorMock->expects($this->any())->method('getRunningJobs')->will($this->returnValue(array($jobs)));
//        $method3->invokeArgs($executorMock, array($jobs));
//        $this->assertEquals($jobs, $method2->invokeArgs($executorMock, array()));
//
//
//
//    }

    protected function assembleShellJobString($command)
    {
        return $this->configuration->getPhpPath() . ' ' . $this->configuration->getScriptPath() . $command;
    }


}