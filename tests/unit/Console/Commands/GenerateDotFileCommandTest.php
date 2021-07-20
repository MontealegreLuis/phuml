<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PHPUnit\Framework\TestCase;
use PhUml\Console\PhUmlApplication;
use PhUml\Parser\InvalidDirectory;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class GenerateDotFileCommandTest extends TestCase
{
    /** @test */
    function it_fails_to_execute_if_the_arguments_are_missing()
    {
        $this->expectException(RuntimeException::class);

        $this->tester->execute([
            'command' => $this->command->getName(),
        ]);
    }

    /** @test */
    function it_fails_to_generate_a_dot_file_if_directory_with_classes_does_not_exist()
    {
        $this->expectException(InvalidDirectory::class);

        $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => 'invalid-directory',
            'output' => $this->dotFile,
        ]);
    }

    /** @test */
    function it_generates_the_dot_file_of_a_given_directory()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => $this->pathToCode,
            'output' => $this->dotFile,
            '--associations' => true,
        ]);

        $this->assertEquals(0, $status);
        $this->assertFileExists($this->dotFile);
    }

    /** @test */
    function it_generates_a_dot_file_searching_for_classes_recursively()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => $this->pathToCode,
            'output' => $this->dotFile,
            '--recursive' => true,
            '--associations' => true,
        ]);

        $this->assertEquals(0, $status);
        $this->assertFileExists($this->dotFile);
    }

    /** @test */
    function it_generates_a_dot_file_excluding_private_and_protected_members()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => $this->pathToCode,
            'output' => $this->dotFile,
            '--recursive' => true,
            '--associations' => true,
            '--hide-protected' => true,
            '--hide-private' => true,
        ]);

        $this->assertEquals(0, $status);
        $this->assertFileExists($this->dotFile);
    }

    /** @before */
    function let()
    {
        $application = new PhUmlApplication();
        $this->command = $application->find('phuml:dot');
        $this->tester = new CommandTester($this->command);
        $this->pathToCode = __DIR__ . '/../../../resources/.code';
        $this->dotFile = __DIR__ . '/../../../resources/.output/dot.gv';
        if (file_exists($this->dotFile)) {
            unlink($this->dotFile);
        }
    }

    private string $dotFile;

    private Command $command;

    private CommandTester $tester;

    private string $pathToCode;
}
