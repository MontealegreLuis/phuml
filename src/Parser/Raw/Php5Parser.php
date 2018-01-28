<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhUml\Parser\Raw\Builders\Filters\MembersFilter;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\Visitors\ClassVisitor;
use PhUml\Parser\Raw\Visitors\InterfaceVisitor;

/**
 * It traverses the AST of all the files and interfaces found by the `CodeFinder` and builds a
 * `RawDefinitions` object
 *
 * In order to create the collection of raw definitions it uses two visitors
 *
 * - The `ClassVisitor` which builds `RawDefinitions` for classes
 * - The `InterfaceVisitor` which builds `RawDefinitions` for interfaces
 */
class Php5Parser extends PhpParser
{
    /** @param MembersFilter[] $filters */
    public function __construct(RawDefinitions $definitions = null, array $filters = [])
    {
        parent::__construct(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP5),
            $definitions ?? new RawDefinitions(),
            $filters
        );
    }

    protected function buildTraverser(): NodeTraverser
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new ClassVisitor($this->definitions, new RawClassBuilder($this->filters)));
        $traverser->addVisitor(new InterfaceVisitor($this->definitions, new RawInterfaceBuilder($this->filters)));
        return $traverser;
    }
}
