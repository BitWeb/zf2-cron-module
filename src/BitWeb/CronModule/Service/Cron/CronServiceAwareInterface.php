<?php

namespace BitWeb\CronModule\Service\Cron;

use BitWeb\CronModule\Service\CronService;

interface CronServiceAwareInterface
{
    public function setCronService(CronService $cronService);

    public function getCronService();
}