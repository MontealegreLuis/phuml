<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Variables\TypeDeclaration;

/**
 * It represents a class variable
 */
final class StaticAttribute extends Attribute
{
    public function __construct(string $name, Visibility $modifier, TypeDeclaration $type)
    {
        parent::__construct($name, $modifier, $type);
        $this->isStatic = true;
    }
}
