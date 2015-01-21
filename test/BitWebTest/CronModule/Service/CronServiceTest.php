<?php

namespace BitWebTest\CronModule\Service;

use BitWeb\CronModule\Configuration;
use BitWeb\CronModule\Executor\Executor;
use BitWeb\CronModule\Service\CronService;
use BitWebTest\CronModule\TestAsset\TestService;
use Cron\Cron;
use Cron\Job\ShellJob;
use Cron\Resolver\ArrayResolver;
use Cron\Schedule\CrontabSchedule;

class CronServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Configuration
     */
    public $configuration;

    public function setUp()
    {
         $this->configuration = new Configuration(include __DIR__ . '/../TestAsset/config.php');
    }

    protected static function getMethod($class, $name)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testCanCreate()
    {
        $this->assertInstanceOf(CronService::class, new CronService($this->configuration));
    }

    public function testGetResolverInstance()
    {
        $this->assertInstanceOf(ArrayResolver::class, (new CronService($this->configuration))->getResolver());
    }

    public function testGetExecutorInstance()
    {
        $service = new CronService($this->configuration);
        $method = self::getMethod(CronService::class, 'getExecutor');
        $this->assertInstanceOf(Executor::class, $method->invokeArgs($service, []));
    }

    public function testInitCron()
    {
        $service = new CronService($this->configuration);
        $method = self::getMethod(CronService::class, 'initCron');
        $method->invokeArgs($service, []);
        $this->assertInstanceOf(Cron::class, \PHPUnit_Framework_Assert::readAttribute($service, 'cron'));
        $this->assertInstanceOf(Executor::class, \PHPUnit_Framework_Assert::readAttribute($service, 'executor'));
        $this->assertInstanceOf(ArrayResolver::class, \PHPUnit_Framework_Assert::readAttribute($service, 'resolver'));
    }

    public function testRun()
    {
        $jobs = [];
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
        $executorMock = $this->getMock(Executor::class);
        $executorMock->expects($this->any())->method('isRunning')->will($this->returnValue(true));
        $executorMock->expects($this->any())->method('getRunningJobs')->will($this->returnValue([$job]));
        $service = new CronService($this->configuration);
        $service->setExecutor($executorMock);
        $service->run();
        sleep(3);
    }

    public function testCronServiceAwareTrait()
    {
        $service = new TestService();
        $service->setCronService(new CronService($this->configuration));
        $this->assertInstanceOf(CronService::class, $service->getCronService());
    }

    protected function assembleShellJobString($command)
    {
        return $this->configuration->getPhpPath() . ' ' . $this->configuration->getScriptPath() . $command;
    }
}
