<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Parser\Code\PhpCodeParser;

/**
 * It inspects a directory finding all the files with PHP code and saves their contents
 *
 * The contents of the files are used by the `PhpParser` to build a `Codebase`
 *
 * @see PhpCodeParser::parse()
 */
interface CodeFinder
{
    public function find(CodebaseDirectory $directory): SourceCode;
}
