<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

final class SourceCode
{
    /** @var string[]  */
    private array $fileContents;

    public function __construct()
    {
        $this->fileContents = [];
    }

    public function add(string $sourceCode): void
    {
        $this->fileContents[] = $sourceCode;
    }

    /** @return string[] */
    public function fileContents(): array
    {
        return $this->fileContents;
    }
}
