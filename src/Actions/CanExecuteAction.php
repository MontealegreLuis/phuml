<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use PhUml\Processors\Processor;

/**
 * Listener for the Action classes. It provides feedback before:
 *
 * - Running the parser
 * - Running a processor
 * - Saving the results produced by the processors
 */
interface CanExecuteAction
{
    public function runningParser(): void;

    public function runningProcessor(Processor $processor): void;

    public function savingResult(): void;
}
