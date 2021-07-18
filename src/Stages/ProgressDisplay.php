<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Processors\Processor;

/**
 * It provides feedback before:
 *
 * - Running the parser
 * - Running a processor
 * - Saving the results produced by the processors
 */
interface ProgressDisplay
{
    public function start(): void;

    public function runningParser(): void;

    public function runningProcessor(Processor $processor): void;

    public function savingResult(): void;
}
