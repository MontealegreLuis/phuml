<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property as ParsedProperty;
use PhUml\Code\Properties\Property;
use PhUml\Code\UseStatements;

/**
 * It builds an array of `Property` for a `ClassDefinition` or a `TraitDefinition`
 *
 * It applies one or more `VisibilityFilter`s
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
interface PropertiesBuilder
{
    /**
     * @param ParsedProperty[] $parsedProperties
     * @return Property[]
     */
    public function build(array $parsedProperties, UseStatements $useStatements): array;

    /**
     * @param Node\Param[] $promotedProperties
     * @return Property[]
     */
    public function fromPromotedProperties(array $promotedProperties, UseStatements $useStatements): array;
}
