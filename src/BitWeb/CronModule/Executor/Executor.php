<?php

namespace BitWeb\CronModule\Executor;

class Executor extends \Cron\Executor\Executor
{
    /**
     * @return \Cron\Job\ShellJob[]
     */
    public function getRunningJobs()
    {
        $jobs = [];
        foreach ($this->sets as $set) {
            if ($set->getJob()->isRunning()) {
                $jobs[] = $set->getJob();
            }
        }

        return $jobs;
    }
}
