<?php

namespace BitWebTest\CronModule\TestAsset;

use BitWeb\CronModule\Service\Cron\CronServiceAwareInterface;
use BitWeb\CronModule\Service\Cron\CronServiceAwareTrait;

class TestService implements CronServiceAwareInterface
{
    use CronServiceAwareTrait;

}