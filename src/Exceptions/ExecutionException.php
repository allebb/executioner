<?php

namespace Ballen\Executioner\Exceptions;

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
class ExecutionException extends \Exception
{

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
