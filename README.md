Executioner
===========

Executioner is a PHP library for executing system processes and applications with the ability to pass extra arguments and read CLI output results.

Executioner is written and maintained by Bobby Allen and is licensed under the MIT license.

Requirements
------------

* PHP >= 5.3.x

Installation
------------

The recommended way to install Executioner is by using Composer, simply can add this directly to your application's **composer.json** file (under the ``require`` section) and then manually run ``composer update``.

```json
"require": {
    "ballen/executioner": "2.0.*@dev"
}
```

For those that are not using Composer you can extract the main library class file (``Executioner.php``) from ``src/Ballen/Executioner`` and *require* directly in your PHP projects.

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

License
-------
The MIT License (MIT)
Copyright (c) 2013 - 2014 Bobby Allen <ballen@bobbyallen.me>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.