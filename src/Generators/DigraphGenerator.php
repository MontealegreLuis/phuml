<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Code\Codebase;
use PhUml\Parser\CodeParser;
use PhUml\Processors\GraphvizProcessor;

class DigraphGenerator extends Generator
{
    /** @var GraphvizProcessor */
    protected $digraphProcessor;

    public function __construct(CodeParser $parser, GraphvizProcessor $digraphProcessor)
    {
        parent::__construct($parser);
        $this->digraphProcessor = $digraphProcessor;
    }

    protected function generateDigraph(Codebase $codebase): string
    {
        $this->display()->runningProcessor($this->digraphProcessor);
        return $this->digraphProcessor->process($codebase);
    }
}
