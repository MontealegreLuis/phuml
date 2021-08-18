<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\Methods\Method;
use PhUml\Code\UseStatements;
use PhUml\Parser\Code\Builders\Members\AttributesBuilder;
use PhUml\Parser\Code\Builders\Members\ConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityFilters;

/**
 * It builds the constants, attributes and methods of a definition
 *
 * @see ConstantsBuilder for more details about the constants creation
 * @see AttributesBuilder for more details about the attributes creation
 * @see MethodsBuilder for more details about the methods creation
 */
final class MembersBuilder
{
    public function __construct(
        private ConstantsBuilder $constantsBuilder,
        private AttributesBuilder $attributesBuilder,
        private MethodsBuilder $methodsBuilder,
        private VisibilityFilters $filters,
    ) {
    }

    /**
     * @param Node[] $members
     * @return Constant[]
     */
    public function constants(array $members): array
    {
        /** @var ClassConst[] $constants */
        $constants = array_filter($members, static fn ($attribute): bool => $attribute instanceof ClassConst);

        /** @var ClassConst[] $filteredConstants */
        $filteredConstants = $this->filters->apply($constants);

        return $this->constantsBuilder->build($filteredConstants);
    }

    /**
     * @param Node[] $members
     * @return Attribute[]
     */
    public function attributes(array $members, ?ClassMethod $constructor, UseStatements $useStatements): array
    {
        $attributes = [];
        if ($constructor !== null) {
            $attributes = $this->attributesFromPromotedProperties($constructor, $useStatements);
        }

        /** @var Property[] $properties */
        $properties = array_filter($members, static fn ($attribute): bool => $attribute instanceof Property);

        /** @var Property[] $filteredAttributes */
        $filteredAttributes = $this->filters->apply($properties);

        return array_merge($this->attributesBuilder->build($filteredAttributes, $useStatements), $attributes);
    }

    /**
     * @param ClassMethod[] $methods
     * @return Method[]
     */
    public function methods(array $methods, UseStatements $useStatements): array
    {
        /** @var ClassMethod[] $filteredMethods */
        $filteredMethods = $this->filters->apply($methods);

        return $this->methodsBuilder->build($filteredMethods, $useStatements);
    }

    /** @return Attribute[] */
    private function attributesFromPromotedProperties(ClassMethod $constructor, UseStatements $useStatements): array
    {
        $promotedProperties = array_filter(
            $constructor->getParams(),
            static fn (Node\Param $param) => $param->flags !== 0
        );

        /** @var Node\Param[] $filteredPromotedProperties */
        $filteredPromotedProperties = $this->filters->apply($promotedProperties);

        return $this->attributesBuilder->fromPromotedProperties($filteredPromotedProperties, $useStatements);
    }
}
