<?php

namespace BitWebTest\CronModule\TestAsset;

use Cron\Executor\ExecutorSet;

class Executor extends \BitWeb\CronModule\Executor\Executor
{
    /**
     * @param \Cron\Job\JobInterface[] $jobs
     */
    public function setJobs(array $jobs)
    {
        foreach ($jobs as $job) {
            $set = new ExecutorSet();
            $set->setJob($job);
            $set->setReport($job->createReport());
            $this->sets[] = $set;
        }
    }

    /**
     * @return \Cron\Executor\ExecutorSet[]
     */
    public function getSets()
    {
        return $this->sets;
    }
}