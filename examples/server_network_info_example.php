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
 * An example showing how to view details of a server's network settings eg. ifconfig/ipconfig
 */
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // Windows uses 'ipconfig' to report on the network settings.
    $runner->setApplication('ipconfig');
} else {
    // Linux and UNIX generally uses 'ifconfig' instead...
    $runner->setApplication('ifconfig');
}

// We'll now execute the application and get the 'results'
$runner->execute();

// We'll simply just display the results as plain text...
echo '<h2>Server NIC settings:</h2>';
echo '<pre>' . $runner->resultAsText() . '</pre>';
