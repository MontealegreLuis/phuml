<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\Methods\Method;
use PhUml\Parser\Code\Builders\Members\AttributesBuilder;
use PhUml\Parser\Code\Builders\Members\ConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;

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
    ) {
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
    public function attributes(array $members, ?ClassMethod $constructor): array
    {
        $attributes = [];
        if ($constructor !== null) {
            $attributes = $this->attributesBuilder->fromPromotedProperties($constructor->getParams());
        }

        return array_merge($this->attributesBuilder->build($members), $attributes);
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
