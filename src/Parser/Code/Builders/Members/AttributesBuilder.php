<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node;
use PhUml\Code\Attributes\Attribute;

/**
 * It builds an array of `Attributes` for a `ClassDefinition` or a `TraitDefinition`
 *
 * It applies one or more `VisibilityFilter`s
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
interface AttributesBuilder
{
    /**
     * @param Node[] $parsedAttributes
     * @return Attribute[]
     */
    public function build(array $parsedAttributes): array;

    /**
     * @param Node\Param[] $constructorParameters
     * @return Attribute[]
     */
    public function fromPromotedProperties(array $constructorParameters): array;
}
