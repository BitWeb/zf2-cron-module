<?php
/**
 * Created by PhpStorm.
 * User: kristjan
 * Date: 4/30/14
 * Time: 11:14 AM
 */

namespace BitWeb\CronModule;


use Cron\Resolver\ArrayResolver;

class TestArrayResolver extends ArrayResolver{

    public function __construct(ArrayResolver $resolver) {

    }
    public function getJobs()
    {
        return $this->jobs;
    }
} 