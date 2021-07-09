<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

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
 * @see ProgressDisplay for the details about the events that are tracked
 */
abstract class Generator
{
    private CodeParser $parser;

    public function __construct(CodeParser $parser)
    {
        $this->parser = $parser;
    }

    protected function parseCode(CodeFinder $finder, ProgressDisplay $display): Codebase
    {
        $display->runningParser();
        return $this->parser->parse($finder);
    }

    protected function save(
        Processor $processor,
        OutputContent $content,
        OutputFilePath $path,
        ProgressDisplay $display
    ): void {
        $display->savingResult();
        $processor->saveToFile($content, $path);
    }
}
