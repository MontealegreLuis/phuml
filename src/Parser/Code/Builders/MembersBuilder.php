<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhUml\Parser\Code\Builders\Members\AttributesBuilder;
use PhUml\Parser\Code\Builders\Members\ConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;
use PhUml\Parser\Code\Builders\Members\ParametersBuilder;
use PhUml\Parser\Code\Builders\Members\TypeBuilder;

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
        $this->constantsBuilder = $constantsBuilder ?? new ConstantsBuilder();
        $this->attributesBuilder = $attributesBuilder ?? new AttributesBuilder([]);
        $typeBuilder = new TypeBuilder();
        $this->methodsBuilder = $methodsBuilder ?? new MethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            []
        );
    }

    /**
     * @param \PhpParser\Node[] $members
     * @return \PhUml\Code\Attributes\Constant[]
     */
    public function constants(array $members): array
    {
        return $this->constantsBuilder->build($members);
    }

    /**
     * @param \PhpParser\Node[] $members
     * @return \PhUml\Code\Attributes\Attribute[]
     */
    public function attributes(array $members): array
    {
        return $this->attributesBuilder->build($members);
    }

    /**
     * @param \PhpParser\Node\Stmt\ClassMethod[] $methods
     * @return \PhUml\Code\Methods\Method[]
     */
    public function methods(array $methods): array
    {
        return $this->methodsBuilder->build($methods);
    }
}
