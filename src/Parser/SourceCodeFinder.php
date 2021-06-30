<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use Symfony\Component\Finder\Finder;

/**
 * It inspects a directory finding all the files with PHP code and saves their contents
 *
 * This finder inspect inner directories recursively.
 * The contents of the files are used by the `PhpParser` to build a `Codebase`
 *
 * @see PhpParser::parse()
 */
final class SourceCodeFinder implements CodeFinder
{
    protected Finder $finder;

    private CodebaseDirectory $directory;

    public static function recursive(CodebaseDirectory $directory): SourceCodeFinder
    {
        return new self(new Finder(), $directory);
    }

    public static function nonRecursive(CodebaseDirectory $directory): SourceCodeFinder
    {
        $finder = new Finder();
        $finder->depth(0);
        return new self($finder, $directory);
    }

    private function __construct(Finder $finder, CodebaseDirectory $directory)
    {
        $this->finder = $finder;
        $this->directory = $directory;
    }

    /** @return string[] */
    public function files(): array
    {
        $files = [];
        $this->finder->in($this->directory->absolutePath())->files()->name('*.php')->sortByName();
        foreach ($this->finder as $file) {
            $files[] = $file->getContents();
        }
        return $files;
    }
}
