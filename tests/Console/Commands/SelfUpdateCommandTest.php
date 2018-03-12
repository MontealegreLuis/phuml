<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use Humbug\SelfUpdate\Strategy\GithubStrategy;
use Humbug\SelfUpdate\Updater;
use PHPUnit\Framework\TestCase;
use PhUml\Console\PhUmlApplication;
use PhUml\Console\ProgressDisplay;
use PhUml\Console\UpdaterDisplay;
use Prophecy\Argument;
use Symfony\Component\Console\Tester\CommandTester;

class SelfUpdateCommandTest extends TestCase
{
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


    /** @before */
    function configureCommandTester()
    {
        $this->updater = $this->prophesize(Updater::class);
        $this->updater->setStrategyObject(Argument::type(GithubStrategy::class))->shouldBeCalled();
        $this->display = $this->prophesize(UpdaterDisplay::class);
        $application = new PhUmlApplication(
            new ProgressDisplay(),
            $this->updater->reveal(),
            $this->display->reveal()
        );
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
