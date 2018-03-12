<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use Exception;
use Humbug\SelfUpdate\Strategy\GithubStrategy;
use Humbug\SelfUpdate\Updater;
use PhUml\Console\NoUpdater;
use PhUml\Console\UpdaterDisplay;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SelfUpdateCommand extends Command
{
    private const PACKAGE_NAME = 'phuml/phuml';
    private const FILE_NAME = 'phuml.phar';

    /** @var Updater */
    private $updater;

    /** @var UpdaterDisplay */
    private $display;

    /** @throws \Symfony\Component\Console\Exception\LogicException */
    public function __construct(Updater $updater = null, UpdaterDisplay $display = null)
    {
        parent::__construct();
        $this->updater = $updater ?? new NoUpdater();
        $this->display = $display ?? new UpdaterDisplay();
    }

    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setDescription('Update phuml.phar to most recent stable version.')
            ->addOption(
                'rollback',
                'r',
                InputOption::VALUE_NONE,
                'Rollback to previous version of phUML if available on filesystem.'
            )
            ->addOption(
                'check',
                'c',
                InputOption::VALUE_NONE,
                'Checks if there is an updated version available.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configureUpdaterStrategy();

        if ($input->getOption('rollback')) {
            $this->tryToRollback();
            return;
        }

        if ($input->getOption('check')) {
            $this->tryToCheckForUpdates();
            return;
        }

        $this->tryToUpdate($output);
    }

    private function tryToRollback(): void
    {
        $this->tryAction([$this, 'rollback']);
    }

    private function rollback(): void
    {
        $result = $this->updater->rollback();
        $this->display->rollbackMessage($result);
    }

    private function tryToCheckForUpdates(): void
    {
        $this->display->currentLocalVersion($this->getApplication()->getVersion());
        $this->tryAction([$this, 'showAvailableUpdates']);
    }

    private function showAvailableUpdates(): void
    {
        if ($this->updater->hasUpdate()) {
            $this->display->newVersion($this->updater->getNewVersion());
        } elseif (false === $this->updater->getNewVersion()) {
            $this->display->noUpdatesAvailable();
        } else {
            $this->display->alreadyUpToDate();
        }
    }

    private function tryToUpdate(OutputInterface $output): void
    {
        $output->writeln('Updating...' . PHP_EOL);
        $this->tryAction([$this, 'update']);
        $output->write(PHP_EOL);
    }

    private function update(): void
    {
        $result = $this->updater->update();
        if ($result) {
            $this->display->updateApplied($this->updater->getOldVersion(), $this->updater->getNewVersion());
        } else {
            $this->display->noUpdateApplied($this->updater->getOldVersion());
        }
    }

    private function configureUpdaterStrategy(): void
    {
        $strategy = new GithubStrategy();
        $strategy->setPackageName(self::PACKAGE_NAME);
        $strategy->setPharName(self::FILE_NAME);
        $strategy->setCurrentLocalVersion($this->getApplication()->getVersion());
        $this->updater->setStrategyObject($strategy);
    }

    private function tryAction(callable $action): void
    {
        try {
            $action();
        } catch (Exception $exception) {
            $this->display->error($exception);
        }
    }
}
