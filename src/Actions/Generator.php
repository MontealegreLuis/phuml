<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use LogicException;
use PhUml\Code\Structure;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\Processor;

/**
 * All generators will see the console commands as listeners that will provide feedback
 * to the end users about their progress
 *
 * @see CanExecuteAction for the details about the events that are tracked
 */
abstract class Generator
{
    /** @var CanExecuteAction */
    private $command;

    /** @var CodeParser */
    private $parser;

    public function __construct(CodeParser $parser)
    {
        $this->parser = $parser;
    }

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

    /**
     * @throws \LogicException If the command is missing
     */
    protected function parseCode(CodeFinder $finder): Structure
    {
        $this->command()->runningParser();
        return $this->parser->parse($finder);
    }

    protected function save(Processor $processor, string $content, string $path): void
    {
        $this->command()->savingResult();
        $processor->saveToFile($content, $path);
    }
}
