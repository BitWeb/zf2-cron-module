<?php

namespace BitWeb\CronModule\Controller;

use BitWeb\CronModule\Exception\RuntimeException;
use BitWeb\CronModule\Service\Cron\CronServiceAwareInterface;
use BitWeb\CronModule\Service\Cron\CronServiceAwareTrait;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class IndexController extends AbstractActionController implements CronServiceAwareInterface
{
    use CronServiceAwareTrait;

    public function onDispatch(MvcEvent $e)
    {
        $this->throwIfNotConsoleRequest();
        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        $this->cronService->run();
    }

    protected function throwIfNotConsoleRequest()
    {
        if (!$this->getRequest() instanceof ConsoleRequest) {
            throw new RuntimeException('Only requests form console are allowed.');
        }
    }
} 