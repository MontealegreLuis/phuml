<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use PhUml\Code\Structure;
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

    protected function generateDigraph(Structure $structure): string
    {
        $this->command()->runningProcessor($this->digraphProcessor);
        return $this->digraphProcessor->process($structure);
    }
}
