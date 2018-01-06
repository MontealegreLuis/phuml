<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Actions;

use PhUml\Processors\StatisticsProcessor;

interface CanGenerateStatistics
{
    public function runningParser(): void;

    public function runningProcessor(StatisticsProcessor $processor): void;

    public function savingResult(): void;
}