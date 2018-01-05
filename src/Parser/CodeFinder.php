<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

use Symfony\Component\Finder\Finder;

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


    public function addDirectory(string $directory, bool $recursive = true): void
    {
        if (!$recursive) {
            $this->finder->depth(0);
        }
        $this->finder->in($directory)->files()->name('*.php');
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
