<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use LogicException;

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
