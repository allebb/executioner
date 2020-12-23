# Executioner

Executioner is a PHP library for executing system processes and applications with the ability to pass extra arguments and read CLI output results.

## Requirements

This library is unit tested against PHP 7.3, 7.4 and 8.0!

If you need to use an older version of PHP, you should instead install the 3.x version of this library (see below for details).

## License

This client library is released under the MIT license, a [copy of the license](https://github.com/bobsta63/executioner/blob/master/LICENSE) is provided in this package.

## Installation

To install the package into your project (assuming you are using the Composer package manager) you can simply execute the following command from your terminal in the root of your project folder:

```composer require ballen/executioner```


**If you need to use an older version of PHP, version 3.x.x supports PHP 5.3, 5.4, 5.5, 5.6, 7.0, 7.1 and 7.2, you can install this version using Composer with this command instead:**

```shell
composer require ballen/executioner ^3.0
```

## Usage example

Example of retrieving IP address information for the server.
```php

use Ballen\Executioner\Executioner;

$run = new Executioner();

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $run->setApplication('ipconfig'); // Server is Microsoft Windows!
} else {
    $run->sudo() // Lets assume we must run 'sudo' to get the output...
        ->setApplication('ifconfig');
}
$run->execute();

// We'll simply just display the results as a plain text string...
echo $run->resultAsText();
```

An example of getting the PHP version number using ``php -v`` terminal command.

```php
use Ballen\Executioner\Executioner;

/**
 * A shorthand version of using the factory method to create a new instance
 * and then chaining the other methods and getting the response.
 */
$php_version = Executioner::make('php')
    ->addArgument('-v')
    ->execute()
    ->resultAsArray();
$words = explode(' ', $php_version[0]); // Split the words from the first line of the output!
$extracted_version_number = $words[1]; // The second word in the raw outputted line should be the version number :)
echo 'The extacted version number is: ' . $extracted_version_number . '';

// The extacted version number is: 7.0.0
```

These examples can also be found in the [examples](examples) directory.

## Support

I am happy to provide support via. my personal email address, so if you need a hand drop me an email at: [ballen@bobbyallen.me]().