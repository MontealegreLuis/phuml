<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhpParser\Node\Stmt;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;
use PhUml\Parser\Code\Builders\InterfaceDefinitionBuilder;
use PhUml\Parser\Code\Builders\TraitDefinitionBuilder;
use PhUml\Parser\CodeFinder;

/**
 * It traverses the AST of all the files and interfaces found by the `CodeFinder` and builds a
 * `Codebase` object
 *
 * In order to create the collection of definitions it uses the following visitors
 *
 * - The `ClassVisitor` which builds `ClassDefinition`s
 * - The `InterfaceVisitor` which builds `InterfaceDefinition`s
 * - The `TraitVisitor` which builds `TraitDefinition`s
 */
final class PhpCodeParser
{
    /** @var Parser */
    private $parser;

    /** @var PhpTraverser */
    private $traverser;

    public function __construct(
        ClassDefinitionBuilder $classBuilder = null,
        InterfaceDefinitionBuilder $interfaceBuilder = null,
        TraitDefinitionBuilder $traitBuilder = null
    ) {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new PhpTraverser(
            $classBuilder ?? new ClassDefinitionBuilder(),
            $interfaceBuilder ?? new InterfaceDefinitionBuilder(),
            $traitBuilder ?? new TraitDefinitionBuilder()
        );
    }

    public function parse(CodeFinder $finder): Codebase
    {
        foreach ($finder->files() as $code) {
            /** @var Stmt[] $nodes Since the parser is run in throw errors mode */
            $nodes = $this->parser->parse($code);
            $this->traverser->traverse($nodes);
        }
        return $this->traverser->codebase();
    }
}
