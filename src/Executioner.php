<?php

namespace Ballen\Executioner;

/**
 * Executioner Process Execution Library
 * Executioner is a PHP library for executing system processes
 * and applications with the ability to pass extra arguments and read
 *  CLI output results.
 *
 * @author  Bobby Allen <ballen@bobbyallen.me>
 * @license http://opensource.org/licenses/MIT
 * @link    https://github.com/allebb/executioner
 */
use Ballen\Collection\Collection;

class Executioner
{

    /**
     * The full system path to the application/process you want to exectute.
     *
     * @var string
     */
    private $applicationPath;

    /**
     * Stores application arguments to be parsed with the application.
     *
     * @var Collection
     */
    private $applicationArguments;

    /**
     * Stores the CLI response.
     *
     * @var Collection
     */
    private $executionResponse;

    /**
     * Stores the CLI execution errors.
     *
     * @var Collection
     */
    private $executionErrors;

    /**
     * Execute the command using sudo?
     *
     * @var boolean
     */
    private $sudo = false;

    /**
     * Redirect stderr to stdout?
     *
     * @var boolean
     */
    private $stdError = false;

    /**
     * Executioner constructor.
     */
    public function __construct()
    {
        $this->applicationArguments = new Collection();
        $this->executionResponse = new Collection();
        $this->executionErrors = new Collection();
    }

    /**
     * Optional way to create new instance
     *
     * @param string $application The command to run.
     * @return Executioner
     */
    public static function make($application)
    {
        return (new Executioner())->setApplication($application);
    }

    /**
     * Adds an argument to be added to the execution string.
     *
     * @param string $argument
     * @return Executioner
     */
    public function addArgument($argument)
    {
        $this->applicationArguments->push($argument);
        return $this;
    }

    /**
     * Sets the application and path of which to be executed.
     *
     * @param string $application The full system path to the application to execute.
     * @return Executioner
     */
    public function setApplication($application)
    {
        $this->applicationPath = $application;
        return $this;
    }

    /**
     * Retrieves the compiled command.
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->compileCommand();
    }

    /**
     * Compiles the command that will be executed
     *
     * @return string
     */
    private function compileCommand()
    {
        $command = '';

        if ($this->sudo) {
            $command = 'sudo ';
        }
        $command .= escapeshellcmd($this->applicationPath) . $this->generateArguments();
        if ($this->stdError) {
            $command .= ' 2>&1';
        }
        return $command;
    }

    /**
     * Generates a list of arguments to be appended onto the executed path.
     *
     * @return string The generated list of arguments.
     */
    protected function generateArguments()
    {
        $arguments = '';
        if (!$this->applicationArguments->isEmpty()) {
            $arguments = ' ' . $this->applicationArguments->implode();
        }
        return $arguments;
    }

    /**
     * Ensure that the process is called with 'sudo' (*NIX systems only)
     *
     * @param bool $enabled Enable (or disable) sudo.
     * @return Executioner
     */
    public function sudo($enabled = true)
    {
        $this->sudo = $enabled;
        return $this;
    }

    /**
     * Enable stderr redirection to stdout.
     *
     * @param boolean $enabled Enable (or disable) redirection to std.
     * @return Executioner
     */
    public function stderr($enabled = true)
    {
        $this->stdError = $enabled;
        return $this;
    }

    /**
     * Executes the process.
     *
     * @return array
     * @throws Exceptions\ExecutionException
     */
    private function exceuteProcess()
    {
        $command = $this->compileCommand();
        exec($command, $result, $status);
        if ($status > 0) {
            throw new Exceptions\ExecutionException('Unknown error occurred when attempting to execute: ' . $command . PHP_EOL);
        }
        return $result;
    }

    /**
     * Executes the application with configured arguments.
     *
     * @return Executioner
     * @throws Exceptions\ExecutionException
     */
    public function execute()
    {
        $this->executionResponse->reset()->push($this->exceuteProcess());
        return $this;
    }

    /**
     * Checks if the application/process is executable.
     *
     * @return boolean
     */
    protected function isExecutable()
    {
        if (is_executable($this->applicationPath)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns an array of class error messages.
     *
     * @return array Array of error messages.
     */
    public function getErrors()
    {
        return $this->executionErrors;
    }

    /**
     * Returns the result (STDOUT) as an array.
     *
     * @return array Result text (STDOUT).
     */
    public function resultAsArray()
    {
        return $this->executionResponse->all()->toArray();
    }

    /**
     * Returns the result (STDOUT) as a JSON string.
     *
     * @return string Result text (STDOUT).
     */
    public function resultAsJSON()
    {
        return $this->executionResponse->all()->toJson();
    }

    /**
     * Returns the result (STDOUT) as seralized data.
     *
     * @return string Result text (STDOUT).
     */
    public function resultAsSerialized()
    {
        return serialize($this->executionResponse->all()->toArray());
    }

    /**
     * Returns the result (stdout) as a raw text string.
     *
     * @return string Result text (STDOUT).
     */
    public function resultAsText()
    {
        $buffer = '';
        foreach ($this->executionResponse->all()->toArray() as $stdout) {
            $buffer .= $stdout . PHP_EOL;
        }
        return $buffer;
    }
}
