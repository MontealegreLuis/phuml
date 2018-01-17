<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhUml\Parser\Raw\RawDefinition;

/**
 * It builds an associative array with meta-information of a class
 *
 * The array has the following structure
 *
 * - `class` The class name
 * - `attributes` The meta-information of the class attributes
 * - `methods` The meta-information of the methods of the class
 * - `implements` The names of the interfaces it implements, if any
 * - `extends` The name of the class it extends, if any
 *
 * @see AttributesBuilder for more details about the attributes information
 * @see MethodsBuilder for more details about the methods information
 */
class RawClassBuilder
{
    /** @var AttributesBuilder */
    private $attributesBuilder;

    /** @var MethodsBuilder */
    private $methodsBuilder;

    public function __construct(
        AttributesBuilder $attributesBuilder = null,
        MethodsBuilder $methodsBuilder = null
    ) {
        $this->attributesBuilder = $attributesBuilder ?? new AttributesBuilder();
        $this->methodsBuilder = $methodsBuilder ?? new MethodsBuilder();
    }

    public function build(Class_ $class): RawDefinition
    {
        return RawDefinition::class([
            'class' => $class->name,
            'attributes' => $this->attributesBuilder->build($class->stmts),
            'methods' => $this->methodsBuilder->build($class),
            'implements' => $this->buildInterfaces($class->implements),
            'extends' => !empty($class->extends) ? end($class->extends->parts) : null,
        ]);
    }

    /** @return string[] */
    private function buildInterfaces(array $implements): array
    {
        return array_map(function (Name $name) {
            return $name->getLast();
        }, $implements);
    }
}
