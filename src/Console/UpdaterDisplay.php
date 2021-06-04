<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console;

use Exception;
use Symfony\Component\Console\Output\OutputInterface;

/** @noRector \Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector */
class UpdaterDisplay
{
    /** @var OutputInterface */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function rollbackMessage(bool $result): void
    {
        if ($result) {
            $this->output->writeln('<info>phUML has been rolled back to prior version.</info>');
        } else {
            $this->output->writeln('<error>Rollback failed for unknown reasons.</error>');
        }
    }

    public function currentLocalVersion(string $version): void
    {
        $this->output->writeln(sprintf(
            'Your current local build version is: <options=bold>%s</options=bold>',
            $version
        ));
    }

    public function newVersion(string $newVersion): void
    {
        $this->output->writeln(sprintf(
            'The current stable build available remotely is: <options=bold>%s</options=bold>',
            $newVersion
        ));
    }

    public function noUpdatesAvailable(): void
    {
        $this->output->writeln('There are no stable builds available.');
    }

    public function alreadyUpToDate(): void
    {
        $this->output->writeln('You have the current stable build installed.');
    }

    public function updateApplied(string $oldVersion, string $newVersion): void
    {
        $this->output->writeln('<info>phUML has been updated.</info>');
        $this->output->writeln(sprintf(
            '<info>Current version is:</info> <options=bold>%s</options=bold>.',
            $newVersion
        ));
        $this->output->writeln(sprintf(
            '<info>Previous version was:</info> <options=bold>%s</options=bold>.',
            $oldVersion
        ));
    }

    public function noUpdateApplied(string $currentVersion): void
    {
        $this->output->writeln('<info>phUML is currently up to date.</info>');
        $this->output->writeln(sprintf(
            '<info>Current version is:</info> <options=bold>%s</options=bold>.',
            $currentVersion
        ));
    }

    public function error(Exception $exception): void
    {
        $this->output->writeln(sprintf('Error: <comment>%s</comment>', $exception->getMessage()));
    }
}
