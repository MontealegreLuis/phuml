<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use PhUml\Code\Structure;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\Processor;

class DigraphGenerator extends Action
{
    /** @var CodeParser */
    protected $parser;

    /** @var GraphvizProcessor */
    protected $digraphProcessor;

    public function __construct(CodeParser $parser, GraphvizProcessor $digraphProcessor)
    {
        $this->parser = $parser;
        $this->digraphProcessor = $digraphProcessor;
    }
    /**
     * @throws \LogicException If the command is missing
     */
    protected function parseCode(CodeFinder $finder): Structure
    {
        $this->command()->runningParser();
        return $this->parser->parse($finder);
    }

    protected function generateDigraph(Structure $structure): string
    {
        $this->command()->runningProcessor($this->digraphProcessor);
        return $this->digraphProcessor->process($structure);
    }

    protected function save(Processor $processor, string $content, string $path): void
    {
        $this->command()->savingResult();
        $processor->saveToFile($content, $path);
    }
}
