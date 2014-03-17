<?php

namespace CronModule;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public $correctConfig = array(
        'phpPath' => 'php',
        'scriptPath' => '/var/www/application/public/',
        'jobs' => array(
            array(
                'command' => 'index.php application cron mail',
                'schedule' => '*/5 * * * *'
            )
        ),

        // timeout in seconds for the process, defaults to 600 seconds
        'timeOut' => 850
    );

    public function testCanCreate()
    {
        $this->assertInstanceOf(Configuration::class, new Configuration($this->correctConfig));
    }

    /**
     * @expectedException \CronModule\Exception\InvalidArgumentException
     */
    public function testThrowsErrorWhenNotArrayOrTraversable()
    {
        new Configuration();
        new Configuration('string');
    }
} 