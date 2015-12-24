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
require_once '../src/Executioner.php';
use Ballen\Executioner\Executioner;

$runner = new Executioner();

/**
 * An example showing how to execute a command and then return the result as an array
 * and further split infomation down if required.
 */
$runner->setApplication('php') // Call the PHP executable to display the version infomation.
    ->addArgument('-v') // Displays the PHP version number!
    ->execute();

// We'll grab the result set as an Array as it's quicker and easier to then just grab the first line (1st element of the array)
$results = $runner->resultAsArray();


// We'll now only return the first line (as that is all we really care about, the other ouputted lines are just copyright info etc.)
echo 'The first line of the output was: <strong>' . $results[0] . '</strong><br>';

// Now we could do some more code to extract only the version from the first line and then just show that!

$words = explode(' ', $results[0]);
$extracted_version_number = $words[1]; // The second word in the raw outputted line should be the version number :)

echo 'The extacted version number is: <strong>' . $extracted_version_number . '</strong><br>';
