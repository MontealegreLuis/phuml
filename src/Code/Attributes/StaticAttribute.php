<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Variables\Variable;

/**
 * It represents a class variable
 */
final class StaticAttribute extends Attribute
{
    public function __construct(Variable $variable, Visibility $modifier)
    {
        parent::__construct($variable, $modifier);
        $this->isStatic = true;
    }
}
