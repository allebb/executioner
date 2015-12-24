<?php
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
require_once '../vendor/autoload.php';
use Ballen\Executioner\Executioner;
use Ballen\Executioner\Exceptions\ExecutionException;

/**
 * An example showing how to execute a command with 'sudo' and redirecting the stderr too!
 */
$run = new Executioner();

try {
    $run->setApplication('service ssh reload') // Lets attempt to restart a non-existent system service.
        ->sudo() // Prefix our command with 'sudo' for non system users!
        ->stderrToStdout() // Redirect stderr to the response too!
        ->execute(); // Execute the application with all arguments.
    echo $run->resultAsText(); // Output the response as a string.
} catch (ExecutionException $ex) {
    echo $ex->getMessage();
}

