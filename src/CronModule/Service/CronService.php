<?php

namespace CronModule\Service;

use Cron\Cron;
use Cron\Job\ShellJob;
use Cron\Resolver\ArrayResolver;
use Cron\Schedule\CrontabSchedule;
use CronModule\Configuration;
use CronModule\Exception\TimeoutException;
use CronModule\Executor\Executor;

class CronService
{
    /**
     * @var Configuration
     */
    protected $configuration = null;

    /**
     * @var ArrayResolver
     */
    protected $resolver = null;

    /**
     * @var Cron
     */
    protected $cron = null;

    /**
     * @var Executor
     */
    protected $executor = null;

    /**
     * @var int
     */
    protected $startTime = null;

    public function __construct(Configuration $configuration)
    {
        $this->setConfiguration($configuration);
    }

    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }


    protected function getResolver()
    {
        if ($this->resolver === null) {
            $this->resolver = new ArrayResolver();
        }

        return $this->resolver;
    }

    protected function getExecutor()
    {
        if ($this->executor === null) {
            $this->executor = new Executor();
        }

        return $this->executor;
    }

    public function run()
    {
        $this->startTime = time();

        $this->initCron();
        $this->addJobs();

        $this->cron->run();
        $this->wait();
        $this->throwErrorIfTimeout();
    }

    protected function initCron()
    {
        $this->cron = new Cron();
        $this->cron->setResolver($this->getResolver());
        $this->cron->setExecutor($this->getExecutor());
    }

    protected function addJobs()
    {
        foreach ($this->configuration->getJobs() as $jobArray) {
            $job = new ShellJob();
            $job->setCommand($this->assembleShellJobString($jobArray['command']));
            $job->setSchedule(new CrontabSchedule($jobArray['schedule']));

            $this->getResolver()->addJob($job);
        }
    }

    protected function assembleShellJobString($command)
    {
        return $this->configuration->getPhpPath() . ' ' . $this->configuration->getScriptPath() . $command;
    }

    protected function wait()
    {
        do {
            sleep(1);
        } while ($this->cron->isRunning() && !$this->checkTimeout());
    }

    protected function checkTimeout()
    {
        $timeout = $this->configuration->getTimeout();
        if ($timeout !== null && $timeout > (time() - $this->startTime)) {
            return false;
        }

        return true;
    }

    protected function throwErrorIfTimeout()
    {
        if ($this->checkTimeout()) {
            throw new TimeoutException($this->assembleErrorString());
        }
    }

    protected function assembleErrorString()
    {
        $string = 'Jobs: ' . PHP_EOL;
        $i = 1;
        foreach ($this->getExecutor()->getRunningJobs() as $job) {
            $string .= $i . '. ' . $job->getProcess()->getCommandLine() . PHP_EOL;
            $i++;
        }

        return $string . ' have taken over ' . $this->configuration->getTimeout() . ' seconds to execute.';
    }
}
