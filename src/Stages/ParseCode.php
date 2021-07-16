<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Code\Codebase;
use PhUml\Generators\ProgressDisplay;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCode;

final class ParseCode
{
    private CodeParser $codeParser;

    private ProgressDisplay $display;

    public function __construct(CodeParser $codeParser, ProgressDisplay $display)
    {
        $this->codeParser = $codeParser;
        $this->display = $display;
    }

    public function __invoke(SourceCode $sourceCode): Codebase
    {
        $this->display->runningParser();
        return $this->codeParser->parse($sourceCode);
    }
}
