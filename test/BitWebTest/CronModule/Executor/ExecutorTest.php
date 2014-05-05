<?php

namespace BitWebTest\CronModule\Executor;

use BitWeb\CronModule\Configuration;
use BitWebTest\CronModule\TestAsset\Executor;
use Cron\Job\ShellJob;
use Cron\Schedule\CrontabSchedule;

class ExecutorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    public $configuration;

    public function setUp()
    {
        $this->configuration = new Configuration(include __DIR__ . '/../TestAsset/config.php');
    }

    public function testGetRunningJobs()
    {
        $executor = new Executor();

        $jobs = [];
        foreach ($this->configuration->getJobs() as $jobArray) {
            $jobMock = $this->getMock(ShellJob::class, ['isRunning']);
            $jobMock->setCommand($this->assembleShellJobString($jobArray['command']));
            $jobMock->setSchedule(new CrontabSchedule($jobArray['schedule']));
            $jobMock->expects($this->any())->method('isRunning')->will($this->returnValue(true));

            $jobs[] = $jobMock;
        }

        $executor->setJobs($jobs);

        $this->assertEquals($jobs, $executor->getRunningJobs());
    }

    protected function assembleShellJobString($command)
    {
        return $this->configuration->getPhpPath() . ' ' . $this->configuration->getScriptPath() . $command;
    }
} 
