<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\SourceCode;

final class StringCodeFinder implements CodeFinder
{
    private SourceCode $sourceCode;

    public function __construct()
    {
        $this->sourceCode = new SourceCode();
    }

    public function add(string $definition): void
    {
        $this->sourceCode->add($definition);
    }

    public function find(CodebaseDirectory $directory): SourceCode
    {
        return $this->sourceCode;
    }
}
