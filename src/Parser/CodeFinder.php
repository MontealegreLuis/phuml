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
 * This finder inspect inner directories recursively.
 * The contents of the files are used by the `TokenParser` to build the `RawDefinitions`
 */
class CodeFinder
{
    /** @var Finder */
    protected $finder;

    /** @var string[] */
    private $files;

    public function __construct()
    {
        $this->finder = new Finder();
        $this->files = [];
    }

    public function addDirectory(CodebaseDirectory $codebaseDirectory): void
    {
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
