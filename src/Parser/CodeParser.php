<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Code\Codebase;
use PhUml\Parser\Code\ExternalDefinitionsResolver;
use PhUml\Parser\Code\PhpCodeParser;
use PhUml\Parser\Code\PhpParser;

/**
 * It takes the files found by the `CodeFinder` and turns them into a `Codebase`
 *
 * A `Codebase` is a collection of `Definition`s (classes, interfaces and traits)
 *
 * It will call the `ExternalDefinitionsResolver` to add generic `Definition`s for classes,
 * interfaces and traits that do not belong directly to the current codebase
 *
 * These external definitions are either built-in or from third party libraries
 */
final class CodeParser
{
    /** @var PhpParser */
    private $parser;

    /** @var ExternalDefinitionsResolver */
    private $resolver;

    public function __construct(PhpParser $parser = null, ExternalDefinitionsResolver $resolver = null)
    {
        $this->parser = $parser ?? new PhpCodeParser();
        $this->resolver = $resolver ?? new ExternalDefinitionsResolver();
    }

    /**
     * The parsing process is as follows
     *
     * 1. Parse the code and populate the `Codebase` with definitions
     * 2. Add external definitions (built-in/third party), if needed
     */
    public function parse(CodeFinder $finder): Codebase
    {
        $codebase = $this->parser->parse($finder);
        $this->resolver->resolve($codebase);

        return $codebase;
    }
}
