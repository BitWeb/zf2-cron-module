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

    public function run()
    {
        $this->initResolver();
        $this->initExecutor();
        $this->initCron();
        $this->addJobs();

        $this->startTime = time();

        $this->cron->run();
        $this->wait();
        $this->throwErrorIfTimeout();
    }

    protected function initResolver()
    {
        $this->resolver = new ArrayResolver();
    }

    protected function initExecutor()
    {
        $this->executor = new Executor();
    }

    protected function initCron()
    {
        $this->cron = new Cron();
        $this->cron->setResolver($this->resolver);
        $this->cron->setExecutor($this->executor);
    }

    protected function addJobs()
    {
        foreach ($this->configuration->getJobs() as $jobArray) {
            $job = new ShellJob();
            $job->setCommand($this->assembleShellJobString($jobArray['command']));
            $job->setSchedule(new CrontabSchedule($jobArray['schedule']));

            $this->resolver->addJob($job);
        }
    }

    protected function assembleShellJobString($command)
    {
        return $this->configuration->getPhpPath() . ' ' . $this->configuration->getScriptPath() . $command;
    }

    protected function wait()
    {
        do  {
            sleep(1);
        }
        while ($this->cron->isRunning() && !$this->checkTimeout());
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
        $string = 'Jobs: ';
        $i = 1;
        foreach ($this->executor->getRunningJobs() as $job) {
            $string .= $i . '. ' . $job->getProcess()->getCommandLine() . ' ';
            $i++;
        }

        return $string . ' have taken over ' . $this->configuration->getTimeout() . ' seconds to execute.';
    }
}
