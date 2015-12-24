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
     * Specifies the method (PHP function) of which to use to execute the applicaiton.
     * @var string
     */
    private $run_method = 'exec';

    public function __construct()
    {
        $this->application_arguments = new Collection();
        $this->exectuion_response = new Collection();
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
     * Exceutes the command using PHP's exec() function.
     * @return \Ballen\Executioner\Executioner
     */
    public function asExec()
    {
        $this->run_method = 'exec';
        return $this;
    }

    /**
     * Exceutes the command using PHP's passthru() function.
     * @return \Ballen\Executioner\Executioner
     */
    public function asPassthru()
    {
        $this->run_method = 'passthru';
        return $this;
    }

    /**
     * Executes the appliaction with configured arguments.
     * @return boolean
     * @throws Exceptions\ExecutionException
     */
    public function execute()
    {
        if (!exec($this->application_path . $this->generateArguments(), $result)) {
            throw new Exceptions\ExecutionException('Error occured when attempting to execute: ' . $this->application_path . $this->generateArguments());
        }
        $this->exectuion_response->reset()->push($result);
        return true;
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
        $buffer = (string) '';
        foreach ($this->exectuion_response->all()->toArray() as $stdout) {
            $buffer .= $stdout . PHP_EOL;
        }
        return $buffer;
    }
}
