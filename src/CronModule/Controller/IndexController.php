<?php

namespace CronModule\Controller;

use CronModule\Exception\RuntimeException;
use CronModule\Service\Cron\CronServiceAwareInterface;
use CronModule\Service\Cron\CronServiceAwareTrait;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController implements CronServiceAwareInterface
{
    use CronServiceAwareTrait;

    public function indexAction()
    {
        $this->throwIfNotConsoleRequest();
        $this->cronService->run();
    }

    protected function throwIfNotConsoleRequest()
    {
        if (!$this->getRequest() instanceof ConsoleRequest) {
            throw new RuntimeException('Only requests form console are allowed.');
        }
    }
} 