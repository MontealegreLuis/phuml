<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use LogicException;

/**
 * All Action classes will see the console commands as listeners that will provide feedback
 * to the end users about their progress
 *
 * @see CanExecuteAction for the details about the events that are tracked
 */
abstract class Action
{
    /** @var CanExecuteAction */
    protected $command;

    public function attach(CanExecuteAction $command): void
    {
        $this->command = $command;
    }

    /** @throws LogicException */
    protected function command(): CanExecuteAction
    {
        if (!$this->command) {
            throw new LogicException('No command was attached');
        }
        return $this->command;
    }
}
