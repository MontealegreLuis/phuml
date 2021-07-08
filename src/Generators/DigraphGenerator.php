<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Code\Codebase;
use PhUml\Parser\CodeParser;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\OutputContent;

class DigraphGenerator extends Generator
{
    protected GraphvizProcessor $digraphProcessor;

    public function __construct(CodeParser $parser, GraphvizProcessor $digraphProcessor)
    {
        parent::__construct($parser);
        $this->digraphProcessor = $digraphProcessor;
    }

    protected function generateDigraph(Codebase $codebase): OutputContent
    {
        $this->display()->runningProcessor($this->digraphProcessor);
        return $this->digraphProcessor->process($codebase);
    }
}
