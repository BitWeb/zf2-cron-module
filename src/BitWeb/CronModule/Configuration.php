<?php

namespace BitWeb\CronModule;

use BitWeb\Stdlib\AbstractConfiguration;
use Zend\Stdlib;

class Configuration extends AbstractConfiguration
{
    /**
     * Array of shell jobs.
     *
     * @var array
     */
    protected $jobs = [];

    /**
     * PHP executable path for using shell jobs.
     *
     * @var string
     */
    protected $phpPath = null;

    /**
     * Base path for script.
     *
     * @var string
     */
    protected $scriptPath = null;

    /**
     * Timeout in seconds for the process. Defaults to 600 seconds. null for no timeout.
     *
     * @var int
     */
    protected $timeout = 600;

    /**
     * @param array $shellJobs
     * @return $this
     */
    public function setJobs(array $shellJobs = [])
    {
        $this->jobs = $shellJobs;

        return $this;
    }

    /**
     * @return array
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @param $key
     * @param array $job
     * @return $this
     */
    public function setJob($key, array $job = [])
    {
        $this->jobs[$key] = $job;

        return $this;
    }

    /**
     * @param $key
     * @return array
     */
    public function getJob($key)
    {
        return $this->jobs[$key];
    }

    /**
     * @return boolean
     */
    public function hasJobs()
    {
        return (bool) (count($this->jobs) > 0);
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPhpPath($path = null)
    {
        $this->phpPath = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhpPath()
    {
        return $this->phpPath;
    }

    /**
     * @param null $scriptPath
     * @return $this
     */
    public function setScriptPath($scriptPath = null)
    {
        $this->scriptPath = $scriptPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getScriptPath()
    {
        if (!$this->scriptPath) {
            return getcwd() . DIRECTORY_SEPARATOR  . 'public' . DIRECTORY_SEPARATOR;
        }
        return $this->scriptPath;
    }

    /**
     * @param $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}
