<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Name as ClassDefinitionName;
use PhUml\Code\Name as TraitName;
use PhUml\Parser\Code\Builders\Members\AttributesBuilder;
use PhUml\Parser\Code\Builders\Members\ConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;

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
            ClassDefinitionName::from($class->name),
            $this->methodsBuilder->build($class->getMethods()),
            $this->constantsBuilder->build($class->stmts),
            !empty($class->extends) ? ClassDefinitionName::from(end($class->extends->parts)) : null,
            $this->attributesBuilder->build($class->stmts),
            $this->buildInterfaces($class->implements),
            $this->buildTraits($class->stmts)
        );
    }

    /** @return ClassDefinitionName[] */
    protected function buildInterfaces(array $implements): array
    {
        return array_map(function (Name $name) {
            return ClassDefinitionName::from($name->getLast());
        }, $implements);
    }

    /** @param Node[] $nodes */
    protected function buildTraits(array $nodes): array
    {
        $useStatements = array_filter($nodes, function (Node $node) {
            return $node instanceof TraitUse;
        });

        if (empty($useStatements)) {
            return [];
        }

        $traits = [];
        /** @var TraitUse  $use */
        foreach ($useStatements as $use) {
            $traits = $this->traitNames($use, $traits);
        }

        return $traits;
    }

    /**
     * @param Name[] $traits
     * @return TraitName[]
     */
    protected function traitNames(TraitUse $use, array $traits): array
    {
        foreach ($use->traits as $name) {
            $traits[] = TraitName::from($name->getLast());
        }
        return $traits;
    }
}
