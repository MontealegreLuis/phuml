<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Parser\Code\PhpCodeParser;
use Symfony\Component\Finder\Finder;

/**
 * It inspects a directory finding all the files with PHP code and saves their contents
 *
 * This finder inspect inner directories recursively.
 * The contents of the files are used by the `PhpParser` to build a `Codebase`
 *
 * @see PhpCodeParser::parse()
 */
final class SourceCodeFinder implements CodeFinder
{
    public static function recursive(): SourceCodeFinder
    {
        return new self(new Finder());
    }

    public static function nonRecursive(): SourceCodeFinder
    {
        $finder = new Finder();
        $finder->depth(0);
        return new self($finder);
    }

    private function __construct(private Finder $finder)
    {
    }

    public function find(CodebaseDirectory $directory): SourceCode
    {
        $sourceCode = new SourceCode();
        $this->finder->in($directory->absolutePath())->files()->name('*.php')->sortByName();
        foreach ($this->finder as $file) {
            $sourceCode->add($file->getContents());
        }
        return $sourceCode;
    }
}
