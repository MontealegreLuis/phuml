<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use LogicException;
use PhUml\Code\Codebase;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\OutputContent;
use PhUml\Processors\OutputFilePath;
use PhUml\Processors\Processor;

/**
 * All generators will see the console commands as listeners that will provide feedback
 * to the end users about their progress
 *
 * @see ProcessorProgressDisplay for the details about the events that are tracked
 */
abstract class Generator
{
    private ?ProcessorProgressDisplay $display = null;

    private CodeParser $parser;

    public function __construct(CodeParser $parser)
    {
        $this->parser = $parser;
    }

    public function attach(ProcessorProgressDisplay $display): void
    {
        $this->display = $display;
    }

    /** @throws LogicException */
    protected function display(): ProcessorProgressDisplay
    {
        if ($this->display === null) {
            throw new LogicException('No display was attached');
        }
        return $this->display;
    }

    /**
     * @throws LogicException If the command is missing
     */
    protected function parseCode(CodeFinder $finder): Codebase
    {
        $this->display()->runningParser();
        return $this->parser->parse($finder);
    }

    protected function save(Processor $processor, OutputContent $content, OutputFilePath $path): void
    {
        $this->display()->savingResult();
        $processor->saveToFile($content, $path);
    }
}
