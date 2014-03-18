<?php

namespace CronModule;

use Zend\Stdlib;

class Configuration
{
    /**
     * Array of shell jobs.
     *
     * @var array
     */
    protected $jobs = array();

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

    public function __construct($config = null)
    {
        if (null !== $config) {
            if (is_array($config)) {
                $this->processArray($config);
            } elseif ($config instanceof \Traversable) {
                $this->processArray(Stdlib\ArrayUtils::iteratorToArray($config));
            } else {
                throw new Exception\InvalidArgumentException(
                    'Parameter to \\CronModule\\Configuration\'s constructor must be an array or implement the \\Traversable interface'
                );
            }
        }
    }

    protected function processArray($config)
    {
        foreach ($config as $key => $value) {
            $setter = $this->assembleSetterNameFromConfigKey($key);
            $this->{$setter}($value);
        }
    }

    protected function assembleSetterNameFromConfigKey($key)
    {
        $parts = explode('_', $key);
        $parts = array_map('ucfirst', $parts);
        $setter = 'set' . implode('', $parts);
        if (!method_exists($this, $setter)) {
            throw new Exception\BadMethodCallException(
                'The configuration key "' . $key . '" does not have a matching ' . $setter . ' setter method which must be defined'
            );
        }
        return $setter;
    }

    /**
     * @param array $shellJobs
     * @return $this
     */
    public function setJobs(array $shellJobs = array())
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
    public function setJob($key, array $job = array())
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
