<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PHPUnit\Framework\TestCase;
use PhUml\Console\PhUmlApplication;
use PhUml\Parser\InvalidDirectory;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class GenerateStatisticsCommandTest extends TestCase
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
    function it_fails_to_generate_a_diagram_if_directory_with_classes_does_not_exist()
    {
        $this->expectException(InvalidDirectory::class);

        $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => 'invalid-directory',
            'output' => $this->statistics,
        ]);
    }

    /** @test */
    function it_generates_the_statistics_of_a_given_directory_using_the_recursive_option()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => __DIR__ . '/../../../resources/.code',
            'output' => $this->statistics,
            '--recursive' => true,
        ]);

        $this->assertSame(0, $status);
        $this->assertFileExists($this->statistics);
    }

    /** @before */
    function let()
    {
        $application = new PhUmlApplication();
        $this->command = $application->find('phuml:statistics');
        $this->tester = new CommandTester($this->command);
        $this->statistics = __DIR__ . '/../../../resources/.output/statistics.txt';
        if (file_exists($this->statistics)) {
            unlink($this->statistics);
        }
    }

    private Command $command;

    private CommandTester $tester;

    private string $statistics;
}
