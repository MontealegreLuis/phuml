<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Actions;

use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\GraphvizProcessor;

class GenerateDotFile extends Action
{
    /** @var TokenParser */
    private $parser;

    /** @var GraphvizProcessor */
    private $dotProcessor;

    public function __construct(TokenParser $parser, GraphvizProcessor $dotProcessor) {
        $this->parser = $parser;
        $this->dotProcessor = $dotProcessor;
    }

    /**
     * @throws \LogicException If the command is missing
     */
    public function generate(CodeFinder $finder, string $dotFilePath): void
    {
        $this->command()->runningParser();
        $structure = $this->parser->parse($finder);
        $this->command()->runningProcessor($this->dotProcessor);
        $dotLanguage = $this->dotProcessor->process($structure);
        $this->command()->savingResult();
        $this->dotProcessor->writeToDisk($dotLanguage, $dotFilePath);
    }
}
