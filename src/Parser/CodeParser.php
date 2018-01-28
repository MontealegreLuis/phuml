<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Code\Structure;
use PhUml\Parser\Raw\ExternalDefinitionsResolver;
use PhUml\Parser\Raw\PhpParser;
use PhUml\Parser\Raw\Php5Parser;

/**
 * It takes the files found by the `CodeFinder` and turns the into a code `Structure`
 *
 * A code `Structure` is a collection of `Definition`s (classes and interfaces)
 *
 * It will call the `ExternalDefinitionsResolver` to add generic `RawDefinition`s for classes and
 * interfaces that do not belong directly to the current codebase.
 * These external definitions are either built-in or from third party libraries
 */
class CodeParser
{
    /** @var StructureBuilder */
    private $builder;

    /** @var PhpParser */
    private $parser;

    /** @var ExternalDefinitionsResolver */
    private $resolver;

    public function __construct(
        StructureBuilder $builder = null,
        PhpParser $parser = null,
        ExternalDefinitionsResolver $resolver = null
    ) {
        $this->builder = $builder ?? new StructureBuilder();
        $this->parser = $parser ?? new Php5Parser();
        $this->resolver = $resolver ?? new ExternalDefinitionsResolver();
    }

    /**
     * The parsing process is as follows
     *
     * 1. Parse the code and generate the raw definitions
     * 2. Add external definitions (built-in classes/third party libraries), if needed
     * 3. Build the code structure from the raw definitions
     */
    public function parse(CodeFinder $finder): Structure
    {
        $definitions = $this->parser->parse($finder);
        $this->resolver->resolve($definitions);

        return $this->builder->buildFrom($definitions);
    }
}
