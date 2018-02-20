<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Name as ClassDefinitionName;

/**
 * It builds a `ClassDefinition`
 *
 * @see ConstantsBuilder for more details about the constants creation
 * @see AttributesBuilder for more details about the attributes creation
 * @see MethodsBuilder for more details about the methods creation
 */
class ClassDefinitionBuilder
{
    /** @var AttributesBuilder */
    protected $attributesBuilder;

    /** @var MethodsBuilder */
    protected $methodsBuilder;

    /** @var ConstantsBuilder */
    protected $constantsBuilder;

    public function __construct(
        ConstantsBuilder $constantsBuilder = null,
        AttributesBuilder $attributesBuilder = null,
        MethodsBuilder $methodsBuilder = null
    ) {
        $this->constantsBuilder = $constantsBuilder ?? new ConstantsBuilder();
        $this->attributesBuilder = $attributesBuilder ?? new AttributesBuilder([]);
        $this->methodsBuilder = $methodsBuilder ?? new MethodsBuilder([]);
    }

    public function build(Class_ $class): ClassDefinition
    {
        return new ClassDefinition(
            $class->name,
            $this->constantsBuilder->build($class->stmts),
            $this->methodsBuilder->build($class->getMethods()),
            !empty($class->extends) ? ClassDefinitionName::from(end($class->extends->parts)) : null,
            $this->attributesBuilder->build($class->stmts),
            $this->buildInterfaces($class->implements)
        );
    }

    /** @return ClassDefinitionName[] */
    protected function buildInterfaces(array $implements): array
    {
        return array_map(function (Name $name) {
            return ClassDefinitionName::from($name->getLast());
        }, $implements);
    }
}
