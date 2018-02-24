<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TraitUse;
use PhUml\Code\Name;
use PhUml\Code\Name as TraitName;
use PhUml\Code\TraitDefinition;
use PhUml\Parser\Code\Builders\Members\AttributesBuilder;
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;

class TraitDefinitionBuilder
{
    /** @var AttributesBuilder */
    protected $attributesBuilder;

    /** @var MethodsBuilder */
    protected $methodsBuilder;

    public function __construct(
        AttributesBuilder $attributesBuilder = null,
        MethodsBuilder $methodsBuilder = null
    ) {
        $this->attributesBuilder = $attributesBuilder ?? new AttributesBuilder();
        $this->methodsBuilder = $methodsBuilder ?? new MethodsBuilder();
    }

    public function build(Trait_ $trait): TraitDefinition
    {
        return new TraitDefinition(
            Name::from($trait->name),
            $this->methodsBuilder->build($trait->getMethods()),
            $this->attributesBuilder->build($trait->stmts),
            $this->buildTraits($trait->stmts)
        );
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
