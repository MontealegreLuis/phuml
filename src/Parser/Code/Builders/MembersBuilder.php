<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\Methods\Method;
use PhUml\Parser\Code\Builders\Members\AllConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\AttributesBuilder;
use PhUml\Parser\Code\Builders\Members\ConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\FilteredAttributesBuilder;
use PhUml\Parser\Code\Builders\Members\FilteredMethodsBuilder;
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;
use PhUml\Parser\Code\Builders\Members\ParametersBuilder;
use PhUml\Parser\Code\Builders\Members\TypeBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityBuilder;
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
    /** @var ConstantsBuilder */
    private $constantsBuilder;

    /** @var AttributesBuilder */
    private $attributesBuilder;

    /** @var MethodsBuilder */
    private $methodsBuilder;

    public function __construct(
        ConstantsBuilder $constantsBuilder = null,
        AttributesBuilder $attributesBuilder = null,
        MethodsBuilder $methodsBuilder = null
    ) {
        $visibilityBuilder = new VisibilityBuilder();
        $filters = new VisibilityFilters();
        $this->constantsBuilder = $constantsBuilder ?? new AllConstantsBuilder($visibilityBuilder);
        $this->attributesBuilder = $attributesBuilder ?? new FilteredAttributesBuilder(
            $visibilityBuilder,
            $filters
        );
        $typeBuilder = new TypeBuilder();
        $this->methodsBuilder = $methodsBuilder ?? new FilteredMethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            $visibilityBuilder,
            $filters
        );
    }

    /**
     * @param Node[] $members
     * @return Constant[]
     */
    public function constants(array $members): array
    {
        return $this->constantsBuilder->build($members);
    }

    /**
     * @param Node[] $members
     * @return Attribute[]
     */
    public function attributes(array $members): array
    {
        return $this->attributesBuilder->build($members);
    }

    /**
     * @param ClassMethod[] $methods
     * @return Method[]
     */
    public function methods(array $methods): array
    {
        return $this->methodsBuilder->build($methods);
    }
}
