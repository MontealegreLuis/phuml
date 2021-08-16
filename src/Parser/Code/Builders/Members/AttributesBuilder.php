<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\UseStatements;

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
     * @param Property[] $parsedAttributes
     * @return Attribute[]
     */
    public function build(array $parsedAttributes, UseStatements $useStatements): array;

    /**
     * @param Node\Param[] $promotedProperties
     * @return Attribute[]
     */
    public function fromPromotedProperties(array $promotedProperties, UseStatements $useStatements): array;
}
