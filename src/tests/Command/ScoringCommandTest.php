<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ScoringCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:scoring-refresh');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'client_id' => 30,
        ]);
        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Name30', $output);
        $this->assertStringContainsString('Surname30', $output);
        $this->assertStringContainsString('7910', $output);
        $this->assertStringContainsString('user30@yahoo.ru', $output);
        $this->assertStringContainsString('Higher', $output);
        $this->assertStringContainsString('No', $output);
        $this->assertStringContainsString('21', $output);

        // ...
    }
}