<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Actions;

use plProcessor;

interface CanGenerateClassDiagram
{
    public function runningParser(): void;

    public function runningProcessor(plProcessor $processor): void;

    public function savingResult(): void;
}