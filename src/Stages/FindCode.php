<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\SourceCode;

final class FindCode
{
    private CodeFinder $codeFinder;

    private ProgressDisplay $display;

    public function __construct(CodeFinder $codeFinder, ProgressDisplay $display)
    {
        $this->codeFinder = $codeFinder;
        $this->display = $display;
    }

    public function __invoke(CodebaseDirectory $directory): SourceCode
    {
        $this->display->start();
        return $this->codeFinder->find($directory);
    }
}