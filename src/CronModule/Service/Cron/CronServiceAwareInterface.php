<?php

namespace CronModule\Service\Cron;


use CronModule\Service\CronService;

interface CronServiceAwareInterface
{
    public function setCronService(CronService $cronService);

    public function getCronService();
}