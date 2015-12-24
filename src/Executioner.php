<?php

namespace Ballen\Executioner;

/**
 * Executioner Process Execution Library
 *
 * Executioner is a PHP library for executing system processes
 * and applications with the ability to pass extra arguments and read
 *  CLI output results.
 *
 * @author ballen@bobbyallen.me (Bobby Allen)
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/bobsta63/executioner
 *
 */
use Ballen\Collection\Collection;

class Executioner
{

    /**
     * The full system path to the application/process you want to exectute.
     * @var string
     */
    private $application_path;

    /**
     * Stores application arguments to be parsed with the application.
     * @var Collection
     */
    private $application_arguments;

    /**
     * Stores the CLI response.
     * @var Collection
     */
    private $exectuion_response;

    /**
     * Execute the command using sudo?
     * @var boolean
     */
    private $sudo = false;

    /**
     * Redirect stderr to stdout?
     * @var boolean
     */
    private $stderr = false;

    public function __construct()
    {
        $this->application_arguments = new Collection();
        $this->exectuion_response = new Collection();
    }

    /**
     * Optional way to create new instance
     * @param string $application The command to run.
     * @return Executioner
     */
    public static function make($application)
    {
        return (new Executioner())->setApplication($application);
    }

    /**
     * Adds an argument to be added to the execution string.
     * @param string $argument
     * @return \Ballen\Executioner\Executioner
     */
    public function addArgument($argument)
    {
        $this->application_arguments->push($argument);
        return $this;
    }

    /**
     * Sets the application and path of which to be executed.
     * @param string $application The full system path to the application to execute.
     * @return \Ballen\Executioner\Executioner
     */
    public function setApplication($application)
    {
        $this->application_path = $application;
        return $this;
    }

    /**
     * Generates a list of arguments to be appended onto the executed path.
     * @return string The generated list of arguments.
     */
    protected function generateArguments()
    {
        $arguments = '';
        if (!$this->application_arguments->isEmpty()) {
            $arguments = ' ' . $this->application_arguments->implode();
        }
        return $arguments;
    }

    /**
     * Ensure that the process is called with 'sudo' (*NIX systems only)
     * @return \Ballen\Executioner\Executioner
     */
    public function sudo($enabled = true)
    {
        $this->sudo = $enabled;
        return $this;
    }

    /**
     * Enable stderr redirection to stdout.
     * @param boolean $enabled
     * @return \Ballen\Executioner\Executioner
     */
    public function stderr($enabled = true)
    {
        $this->stderror = $enabled;
        return $this;
    }

    /**
     * Executes the process.
     * @return array
     * @throws Exceptions\ExecutionException
     */
    private function exceuteProcess()
    {
        $command = '';
        if ($this->sudo) {
            $command = 'sudo ';
        }
        $command .= escapeshellcmd($this->application_path) . $this->generateArguments();
        if ($this->stderr) {
            $command .= ' 2>&1';
        }
        exec($this->application_path . $this->generateArguments(), $result, $status);
        if ($status > 0) {
            throw new Exceptions\ExecutionException('Unknown error occured when attempting to execute: ' . $command . PHP_EOL);
        }
        return $result;
    }

    /**
     * Executes the appliaction with configured arguments.
     * @return Executioner
     * @throws Exceptions\ExecutionException
     */
    public function execute()
    {
        $this->exectuion_response->reset()->push($this->exceuteProcess());
        return $this;
    }

    /**
     * Checks if the application/process is executable.
     * @return boolean
     */
    protected function isExecutable()
    {
        if (is_executable($this->application_path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns an array of class error messages.
     * @return array Array of error messages.
     */
    public function getErrors()
    {
        return $this->execution_errors;
    }

    /**
     * Returns the result (STDOUT) as an array.
     * @return array Result text (STDOUT).
     */
    public function resultAsArray()
    {
        return $this->exectuion_response->all()->toArray();
    }

    /**
     * Returns the result (STDOUT) as a JSON string.
     * @return string Result text (STDOUT).
     */
    public function resultAsJSON()
    {
        return $this->exectuion_response->all()->toJson();
    }

    /**
     * Returns the result (STDOUT) as seralized data.
     * @return string Result text (STDOUT).
     */
    public function resultAsSerialized()
    {
        return serialize($this->exectuion_response->all()->toArray());
    }

    /**
     * Returns the result (stdout) as a raw text string.
     * @return string Result text (STDOUT).
     */
    public function resultAsText()
    {
        $buffer = '';
        foreach ($this->exectuion_response->all()->toArray() as $stdout) {
            $buffer .= $stdout . PHP_EOL;
        }
        return $buffer;
    }
}
