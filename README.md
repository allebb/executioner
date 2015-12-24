Executioner
===========

Executioner is a PHP library for executing system processes and applications with the ability to pass extra arguments and read CLI output results.

Executioner is written and maintained by Bobby Allen and is licensed under the MIT license.

Requirements
------------

This library is developed and tested for PHP 5.4+

License
-------

This client library is released under the MIT license, a [copy of the license](https://github.com/bobsta63/executioner/blob/master/LICENSE) is provided in this package.

Installation
------------

To install the package into your project (assuming you are using the Composer package manager) you can simply execute the following command from your terminal in the root of your project folder:

```composer require ballen/executioner```

Alternatively you can manually add this library to your project using the following steps, simply edit your project's composer.json file and add the following lines (or update your existing require section with the library like so):

```json
"require": {
        "ballen/executioner": "^3.0"
}
```

Then install the package like so:

```composer update ballen/executioner --no-dev```

For those that are not using Composer you can extract the main library class file (``Executioner.php``) from ``src/`` and *require* directly in your PHP projects.

Examples
--------

A little example showing how to return the PHP version number from calling the ``php -v`` command:-

```php
<?php

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

echo 'The extracted version number is: <strong>' . $extracted_version_number . '</strong><br>';
```

Another example of outputting your server's NIC settings:-

```php
<?php 

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
echo '<pre>' .$runner->resultAsText(). '</pre>';
```

These examples can also be found in the [examples](examples) directory.

Support
-------

I am happy to provide support via. my personal email address, so if you need a hand drop me an email at: [ballen@bobbyallen.me]().