<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use Symfony\Component\Finder\Finder;

/**
 * It inspects a directory finding all the files with PHP code and saves their contents
 *
 * The directory can be inspected recursively or not.
 * The contents of the files are used by the `TokenParser` to build the `RawDefinitions`
 */
class CodeFinder
{
    /** @var Finder */
    private $finder;

    /** @var string[] */
    private $files;

    public function __construct(Finder $finder = null)
    {
        $this->finder = $finder ?? new Finder();
        $this->files = [];
    }

    public function addDirectory(CodebaseDirectory $codebaseDirectory, bool $recursive = true): void
    {
        if (!$recursive) {
            $this->finder->depth(0);
        }
        $this->finder->in($codebaseDirectory->absolutePath())->files()->name('*.php')->sortByName();
        foreach ($this->finder as $file) {
            $this->files[] = $file->getContents();
        }
    }

    /** @return string[] */
    public function files(): array
    {
        return $this->files;
    }
}
