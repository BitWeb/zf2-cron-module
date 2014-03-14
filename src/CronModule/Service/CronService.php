<?php

namespace CronModule\Service;

use Cron\Cron;
use Cron\Executor\Executor;
use Cron\Job\ShellJob;
use Cron\Resolver\ArrayResolver;
use Cron\Schedule\CrontabSchedule;
use CronModule\Configuration;

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
        $this->initCron();
        $this->addJobs();

        $this->cron->run();
    }

    protected function initResolver()
    {
        $this->resolver = new ArrayResolver();
    }

    protected function initCron()
    {
        $this->cron = new Cron();
        $this->cron->setResolver($this->resolver);
        $this->cron->setExecutor(new Executor());
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
} 