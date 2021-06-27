<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

/**
 * It inspects a directory finding all the files with PHP code and saves their contents
 *
 * This finder does not inspect inner directories only the one provided.
 * The contents of the files are used by the `PhpParser` to build a `Codebase`
 *
 * @see PhpParser::parse()
 */
class NonRecursiveCodeFinder extends CodeFinder
{
    public function __construct()
    {
        parent::__construct();
        $this->finder->depth(0);
    }
}
