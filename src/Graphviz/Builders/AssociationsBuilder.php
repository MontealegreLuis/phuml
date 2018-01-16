<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;

interface AssociationsBuilder
{
    /** @return \PhUml\Graphviz\HasDotRepresentation[] */
    public function attributesAssociationsFrom(ClassDefinition $class): array;

    /** @return \PhUml\Graphviz\HasDotRepresentation[] */
    public function parametersAssociationsFom(ClassDefinition $class): array;
}
