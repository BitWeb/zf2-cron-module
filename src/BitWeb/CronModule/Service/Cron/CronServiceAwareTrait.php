<?php

namespace BitWeb\CronModule\Service\Cron;

use BitWeb\CronModule\Service\CronService;

trait CronServiceAwareTrait
{
    /**
     * @var CronService
     */
    protected $cronService;

    public function setCronService(CronService $cronService)
    {
        $this->cronService = $cronService;

        return $this;
    }

    public function getCronService()
    {
        return $this->cronService;
    }
} 