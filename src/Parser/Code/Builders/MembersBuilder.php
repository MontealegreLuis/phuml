<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

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
class MembersBuilder
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
        $this->methodsBuilder = $methodsBuilder ?? new MethodsBuilder([]);
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
     * @param ClassMethod[] $classMethods
     * @return Method[]
     */
    public function methods(array $methods): array
    {
        return $this->methodsBuilder->build($methods);
    }
}
