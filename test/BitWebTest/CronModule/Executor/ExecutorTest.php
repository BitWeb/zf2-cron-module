<?php
/**
 * Created by PhpStorm.
 * User: kristjan
 * Date: 4/30/14
 * Time: 3:50 PM
 */

namespace BitWebTest\CronModule\Executor;


use BitWeb\CronModule\Configuration;
use BitWebTest\CronModule\TestAsset\Executor;
use Cron\Job\ShellJob;
use Cron\Schedule\CrontabSchedule;

class ExecutorTest extends \PHPUnit_Framework_TestCase
{
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

    public function testGetRunningJobs()
    {
        $executor = new Executor();

        $this->configuration = new Configuration($this->correctConfig);
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