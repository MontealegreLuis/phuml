<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Stmt\Trait_;
use PhUml\Code\Name;
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
            $this->attributesBuilder->build($trait->stmts)
        );
    }
}
