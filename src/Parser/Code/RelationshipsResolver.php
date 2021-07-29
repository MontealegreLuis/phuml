<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\Codebase;

/**
 * It checks the parent of a definition, the interfaces it implements, and the traits it uses
 * looking for external definitions
 *
 * An external definition is a class, trait or interface from a third party library, or a built-in class or interface
 */
interface RelationshipsResolver
{
    public function resolve(Codebase $codebase): void;
}
