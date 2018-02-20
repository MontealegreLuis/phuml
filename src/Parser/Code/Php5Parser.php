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
    public function __construct(
        ClassDefinitionBuilder $classBuilder = null,
        InterfaceDefinitionBuilder $interfaceBuilder = null
    ) {
        parent::__construct(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP5),
            new Php5Traverser(
                $classBuilder ?? new ClassDefinitionBuilder(),
                $interfaceBuilder ?? new InterfaceDefinitionBuilder()
            )
        );
    }
}
