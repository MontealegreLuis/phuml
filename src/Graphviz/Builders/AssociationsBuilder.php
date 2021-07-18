<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Graphviz\Edge;

/**
 * It discovers associations between classes/interfaces by inspecting
 *
 * 1. The attributes of a class
 * 2. The parameters injected trough the constructor of a class
 *
 * It creates edges between the definitions when appropriate
 */
interface AssociationsBuilder
{
    /** @return Edge[]*/
    public function fromAttributes(ClassDefinition $class, Codebase $codebase): array;

    /** @return Edge[] */
    public function fromConstructor(ClassDefinition $class, Codebase $codebase): array;
}
