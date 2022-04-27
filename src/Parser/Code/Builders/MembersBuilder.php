<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property as ParsedProperty;
use PhUml\Code\Methods\Method;
use PhUml\Code\Properties\Constant;
use PhUml\Code\Properties\Property;
use PhUml\Code\UseStatements;
use PhUml\Parser\Code\Builders\Members\ConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;
use PhUml\Parser\Code\Builders\Members\PropertiesBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityFilters;

/**
 * It builds the constants, properties and methods of a definition
 *
 * @see ConstantsBuilder for more details about the constants creation
 * @see PropertiesBuilder for more details about the properties creation
 * @see MethodsBuilder for more details about the methods creation
 */
final class MembersBuilder
{
    public function __construct(
        private readonly ConstantsBuilder $constantsBuilder,
        private readonly PropertiesBuilder $propertiesBuilder,
        private readonly MethodsBuilder $methodsBuilder,
        private readonly VisibilityFilters $filters,
    ) {
    }

    /**
     * @param Node[] $members
     * @return Constant[]
     */
    public function constants(array $members): array
    {
        /** @var ClassConst[] $constants */
        $constants = array_filter($members, static fn ($property): bool => $property instanceof ClassConst);

        /** @var ClassConst[] $filteredConstants */
        $filteredConstants = $this->filters->apply($constants);

        return $this->constantsBuilder->build($filteredConstants);
    }

    /**
     * @param Node[] $members
     * @return Property[]
     */
    public function properties(array $members, ?ClassMethod $constructor, UseStatements $useStatements): array
    {
        $properties = [];
        if ($constructor !== null) {
            $properties = $this->fromPromotedProperties($constructor, $useStatements);
        }

        /** @var ParsedProperty[] $parsedProperties */
        $parsedProperties = array_filter($members, static fn ($property): bool => $property instanceof ParsedProperty);

        /** @var ParsedProperty[] $filteredProperties */
        $filteredProperties = $this->filters->apply($parsedProperties);

        return array_merge($this->propertiesBuilder->build($filteredProperties, $useStatements), $properties);
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

    /** @return Property[] */
    private function fromPromotedProperties(ClassMethod $constructor, UseStatements $useStatements): array
    {
        $promotedProperties = array_filter(
            $constructor->getParams(),
            static fn (Node\Param $param) => $param->flags !== 0
        );

        /** @var Node\Param[] $filteredPromotedProperties */
        $filteredPromotedProperties = $this->filters->apply($promotedProperties);

        return $this->propertiesBuilder->fromPromotedProperties($filteredPromotedProperties, $useStatements);
    }
}
