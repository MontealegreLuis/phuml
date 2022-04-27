<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Code\Codebase;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCode;

final class ParseCode
{
    public function __construct(private readonly CodeParser $codeParser, private readonly ProgressDisplay $display)
    {
    }

    public function __invoke(SourceCode $sourceCode): Codebase
    {
        $this->display->runningParser();
        return $this->codeParser->parse($sourceCode);
    }
}
