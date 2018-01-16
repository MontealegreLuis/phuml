<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;

class NoAssociationsBuilder implements AssociationsBuilder
{

    public function attributesAssociationsFrom(ClassDefinition $class): array
    {
        return [];
    }

    public function parametersAssociationsFom(ClassDefinition $class): array
    {
        return [];
    }
}
