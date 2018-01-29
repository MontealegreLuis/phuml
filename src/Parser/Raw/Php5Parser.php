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
    /** @var RawClassBuilder */
    private $rawClassBuilder;

    /** @var RawInterfaceBuilder */
    private $rawInterfaceBuilder;

    /**
     * @param RawDefinitions|null $definitions
     * @param RawClassBuilder|null $rawClassBuilder
     * @param RawInterfaceBuilder|null $rawInterfaceBuilder
     */
    public function __construct(RawDefinitions $definitions = null, RawClassBuilder $rawClassBuilder = null, RawInterfaceBuilder $rawInterfaceBuilder = null)
    {
        $this->rawClassBuilder = $rawClassBuilder ?? new RawClassBuilder();
        $this->rawInterfaceBuilder = $rawInterfaceBuilder ?? new RawInterfaceBuilder();
        parent::__construct(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP5),
            $definitions ?? new RawDefinitions()
        );
    }

    protected function buildTraverser(): NodeTraverser
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new ClassVisitor($this->definitions, $this->rawClassBuilder));
        $traverser->addVisitor(new InterfaceVisitor($this->definitions, $this->rawInterfaceBuilder));
        return $traverser;
    }
}
