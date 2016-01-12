<?php

namespace BitWebTest\CronModule;

use BitWeb\CronModule\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    public $configuration;

    public function setUp()
    {
        $this->configuration = new Configuration(include __DIR__ . '/TestAsset/config.php');
    }

    public function testCanCreate()
    {
        $this->assertInstanceOf(Configuration::class, $this->configuration);
    }

    public function testGetAndSetJobs()
    {
        $configuration = new Configuration();
        $configuration->setJobs($this->configuration->getJobs());
        $this->assertEquals($this->configuration->getJobs(), $configuration->getJobs());
    }

    public function testSetAndGetJob()
    {
        $configuration = new Configuration();
        $job = $this->configuration->getJobs()[0];
        $key = 'testJob';

        $configuration->setJob($key, $job);
        $this->assertEquals($job, $configuration->getJob($key));
    }

    public function testSetAndGetPhpPath()
    {
        $configuration = new Configuration();
        $phpPath = $this->configuration->getPhpPath();

        $configuration->setPhpPath($phpPath);
        $this->assertEquals($phpPath, $configuration->getPhpPath());
    }

    public function testSetAndGetScriptPath()
    {
        $configuration = new Configuration();
        $scriptPath = $this->configuration->getScriptPath();

        $configuration->setScriptPath($scriptPath);
        $this->assertEquals($scriptPath, $configuration->getScriptPath());
    }

    public function testgetDefaultScriptPath()
    {
        $configuration = new Configuration();
        $scriptPath = $this->configuration->getScriptPath();
         
        $this->assertNotNull($scriptPath);
    }

    public function testSetAndGetTimeout()
    {
        $configuration = new Configuration();
        $timeout = $this->configuration->getTimeout();

        $configuration->setTimeout($timeout);
        $this->assertEquals($timeout, $configuration->getTimeout());
    }

    public function testSetAndHasJobs()
    {
        $configuration = new Configuration();
        $configuration->setJobs($this->configuration->getJobs());
        $this->assertTrue($configuration->hasJobs());
    }

    public function testSetAndHasNoJobs()
    {
        $configuration = new Configuration();
        $this->assertFalse($configuration->hasJobs());
    }
} 
