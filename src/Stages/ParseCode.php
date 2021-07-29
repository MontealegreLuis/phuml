<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Code\Codebase;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCode;

final class ParseCode
{
    public function __construct(private CodeParser $codeParser, private ProgressDisplay $display)
    {
    }

    public function __invoke(SourceCode $sourceCode): Codebase
    {
        $this->display->runningParser();
        return $this->codeParser->parse($sourceCode);
    }
}
