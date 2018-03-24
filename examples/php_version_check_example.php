<?php
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
require_once '../vendor/autoload.php';

use Ballen\Executioner\Executioner;

/**
 * A shorthand version of using the factory method to create a new instance
 * and then chaining the other methods and getting the response.
 */
$php_version = Executioner::make('php')
    ->addArgument('-v')
    ->execute()
    ->resultAsArray();

// We'll now only return the first line (as that is all we really care about, the other ouputted lines are just copyright info etc.)
echo 'The first line of the output was: <strong>' . $php_version[0] . '</strong><br>';

// Now we could do some more code to extract only the version from the first line and then just show that!
$words = explode(' ', $php_version[0]);
$extracted_version_number = $words[1]; // The second word in the raw outputted line should be the version number :)

echo 'The extacted version number is: <strong>' . $extracted_version_number . '</strong><br>';
