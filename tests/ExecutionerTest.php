<?php


use Ballen\Executioner\Exceptions\ExecutionException;
use PHPUnit\Framework\TestCase;
use Ballen\Executioner\Executioner;

class ExecutionerTest extends TestCase
{

    public function testCanInitiateANewInstance()
    {
        try {
            $php_version = Executioner::make('php')
                ->addArgument('-v')
                ->execute()
                ->resultAsArray();
        } catch (ExecutionException $exception) {
            $this->fail('Could not execute the requested command.');
        }
        $this->assertTrue(count($php_version) > 0);
    }

    public function testRetrieveStandardCommand()
    {
        $php_version = Executioner::make('php')->addArgument('-v');
        $this->assertEquals('php -v', $php_version->getCommand());
    }

    public function testRetrieveStdErrorCommand()
    {
        $php_version = Executioner::make('php')->addArgument('-v')->stderr();
        $this->assertEquals('php -v 2>&1', $php_version->getCommand());
    }

    public function testRetrieveSudoCommand()
    {
        $php_version = Executioner::make('php')->addArgument('-v')->sudo();
        $this->assertEquals('sudo php -v', $php_version->getCommand());
    }

    public function testRetrieveStdErrorAndSudoCommand()
    {
        $php_version = Executioner::make('php')->addArgument('-v')->stderr()->sudo();
        $this->assertEquals('sudo php -v 2>&1', $php_version->getCommand());
    }

    public function testFileExecutionChecks()
    {
        $validCommandPath = Executioner::make('/usr/bin/whoami');
        $this->assertTrue($validCommandPath->isExecutable());

        $invalidCommandPath = Executioner::make('/usr/bin/random-invalid-command');
        $this->assertFalse($invalidCommandPath->isExecutable());
    }

    public function testCliOuputAsText()
    {
        $php_version = Executioner::make('php')->addArgument('-v')->execute();
        $this->assertStringContainsString('PHP', $php_version->resultAsText());
        $this->assertStringContainsString('Copyright (c) The PHP Group', $php_version->resultAsText());
    }

    public function testCliOuputAsArray()
    {
        $php_version = Executioner::make('php')->addArgument('-v')->execute();
        $result = $php_version->resultAsArray();
        $this->assertIsArray($result);
        $this->assertStringContainsString('PHP', $result[0]);
        $this->assertStringContainsString('Copyright (c)', $result[1]);

        $whoami = Executioner::make('whoami')->execute();
        $this->assertCount(1, $whoami->resultAsArray());
    }

    public function testCliOuputAsJson()
    {
        $php_version = Executioner::make('php')->addArgument('-v')->execute();
        $result = $php_version->resultAsJSON();
        $this->assertIsString($php_version->resultAsJSON($result));
        $this->assertStringContainsString('PHP', $result);
    }

    public function testCliOuputAsSerialised()
    {
        $php_version = Executioner::make('php')->addArgument('-v')->execute();
        $result = $php_version->resultAsSerialized();
        $this->assertStringContainsString('PHP', $result);
        $this->assertStringEndsWith('}', $result);
        $unserialised = unserialize($result);
        $this->assertIsArray($unserialised);
        $this->assertStringContainsString('PHP', $unserialised[0]);
    }

    public function testCliOutputFromFluentApi()
    {
        $run = new Executioner();
        $run->setApplication('whoami');
        $run->addArgument('--version');
        $run->execute();

        $output = $run->resultAsJSON();
        $this->assertStringContainsString('Richard', $output);
        $this->assertStringContainsString('Free Software Foundation', $output);
    }

    public function testGetErrorsFromExecution()
    {
        $php_version = Executioner::make('php')->addArgument('-v')->execute();
        $this->assertEquals(0, $php_version->getErrors()->count());
    }

    public function testFailedCommandExecution()
    {
        $this->expectException(ExecutionException::class);
        $php_version = Executioner::make('phpZ')->execute();
    }


}