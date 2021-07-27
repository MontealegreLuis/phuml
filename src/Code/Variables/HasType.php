<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use PhUml\Code\Name;

interface HasType
{
    /**
     * Returns the definition names referred by this type, if any
     *
     * @return Name[]
     */
    public function references(): array;

    /**
     * This is used to build the `Summary` of a `Structure`
     *
     * @see \PhUml\Code\ClassDefinition::countTypedAttributesByVisibility() for more details
     */
    public function hasTypeDeclaration(): bool;

    /**
     * It is used by the `EdgesBuilder` class to mark an association as resolved
     *
     * @see \PhUml\Graphviz\Builders\EdgesBuilder::needAssociation() for more details
     * @see \PhUml\Graphviz\Builders\EdgesBuilder::markAssociationResolvedFor() for more details
     */
    public function type(): TypeDeclaration;
}
