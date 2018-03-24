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

$run = new Executioner();

/**
 * An example showing how to view details of a server's network settings eg. ifconfig/ipconfig
 */
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // Windows uses 'ipconfig' to report on the network settings.
    $run->setApplication('ipconfig');
} else {
    // Linux and UNIX generally uses 'ifconfig' instead (well run it with 'sudo' too just for the sake of it)...
    $run->sudo()
        ->setApplication('ifconfig');
}

// We'll now execute the application and get the 'results'
$run->execute();

// We'll simply just display the results as plain text...
echo '<h2>Server NIC settings:</h2>';
echo '<pre>' . $run->resultAsText() . '</pre>';
