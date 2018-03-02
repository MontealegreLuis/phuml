<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhpParser\ParserFactory;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;
use PhUml\Parser\Code\Builders\InterfaceDefinitionBuilder;
use PhUml\Parser\Code\Builders\TraitDefinitionBuilder;

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
class Php5Parser extends PhpParser
{
    public function __construct(
        ClassDefinitionBuilder $classBuilder = null,
        InterfaceDefinitionBuilder $interfaceBuilder = null,
        TraitDefinitionBuilder $traitBuilder = null
    ) {
        parent::__construct(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP5),
            new Php5Traverser(
                $classBuilder ?? new ClassDefinitionBuilder(),
                $interfaceBuilder ?? new InterfaceDefinitionBuilder(),
                $traitBuilder ?? new TraitDefinitionBuilder()
            )
        );
    }
}
