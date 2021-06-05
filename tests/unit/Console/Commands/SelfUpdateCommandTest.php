<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use Exception;
use Humbug\SelfUpdate\Strategy\StrategyInterface;
use Humbug\SelfUpdate\Updater;
use PHPUnit\Framework\TestCase;
use PhUml\Console\PhUmlApplication;
use PhUml\Console\ProgressDisplay;
use PhUml\Console\UpdaterDisplay;
use PhUml\Fakes\TextInMemoryOutput;
use Prophecy\Argument;
use Symfony\Component\Console\Tester\CommandTester;

final class SelfUpdateCommandTest extends TestCase
{
    /** @test */
    function it_notifies_error_when_trying_to_rollback()
    {
        $exception = new Exception('Cannot rollback');
        $this->updater->rollback()->willThrow($exception);

        $this->tester->execute([
            'command' => $this->command->getName(),
            '--rollback' => true,
        ]);

        $this->display->error($exception)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_rolls_back_to_a_previous_version()
    {
        $this->updater->rollback()->willReturn(true);

        $this->tester->execute([
            'command' => $this->command->getName(),
            '--rollback' => true,
        ]);


        $this->display->rollbackMessage(true)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_notifies_when_it_fails_to_rollback_to_a_previous_message()
    {
        $this->updater->rollback()->willReturn(false);

        $this->tester->execute([
            'command' => $this->command->getName(),
            '--rollback' => true,
        ]);


        $this->display->rollbackMessage(false)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_notifies_when_there_is_a_new_version_to_install()
    {
        $newVersion = '2.0.0';
        $this->updater->hasUpdate()->willReturn(true);
        $this->updater->getNewVersion()->willReturn($newVersion);

        $this->tester->execute([
            'command' => $this->command->getName(),
            '--check' => true,
        ]);

        $this->display->newVersion($newVersion)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_notifies_when_there_are_no_remote_versions()
    {
        $this->updater->hasUpdate()->willReturn(false);
        $this->updater->getNewVersion()->willReturn(false);

        $this->tester->execute([
            'command' => $this->command->getName(),
            '--check' => true,
        ]);

        $this->display->noUpdatesAvailable()->shouldHaveBeenCalled();
    }

    /** @test */
    function it_notifies_when_the_phar_is_up_to_date()
    {
        $currentVersion = '1.6.1';
        $this->updater->hasUpdate()->willReturn(false);
        $this->updater->getNewVersion()->willReturn($currentVersion);

        $this->tester->execute([
            'command' => $this->command->getName(),
            '--check' => true,
        ]);

        $this->display->alreadyUpToDate()->shouldHaveBeenCalled();
    }

    /** @test */
    function it_updates_to_the_next_stable_version()
    {
        $oldVersion = '1.6.1';
        $newVersion = '1.6.2';
        $this->updater->update()->willReturn(true);
        $this->updater->getOldVersion()->willReturn($oldVersion);
        $this->updater->getNewVersion()->willReturn($newVersion);

        $this->tester->execute([
            'command' => $this->command->getName(),
        ]);

        $this->display->updateApplied($oldVersion, $newVersion)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_notifies_when_the_current_local_version_is_the_latest_version()
    {
        $currentVersion = '1.6.1';
        $this->updater->update()->willReturn(false);
        $this->updater->getOldVersion()->willReturn($currentVersion);

        $this->tester->execute([
            'command' => $this->command->getName(),
        ]);

        $this->display->noUpdateApplied($currentVersion)->shouldHaveBeenCalled();
    }

    /** @before */
    function let()
    {
        $this->updater = $this->prophesize(Updater::class);
        $this->updater->setStrategyObject(Argument::type(StrategyInterface::class))->shouldBeCalled();
        $this->display = $this->prophesize(UpdaterDisplay::class);
        $application = new PhUmlApplication(new ProgressDisplay(new TextInMemoryOutput()));
        $application->add(new SelfUpdateCommand($this->updater->reveal(), $this->display->reveal()));
        $this->command = $application->find('self-update');
        $this->tester = new CommandTester($this->command);
    }

    /** @var SelfUpdateCommand */
    private $command;

    /** @var Updater */
    private $updater;

    /** @var UpdaterDisplay */
    private $display;

    /** @var CommandTester */
    private $tester;
}
