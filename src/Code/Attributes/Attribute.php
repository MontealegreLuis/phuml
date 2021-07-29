<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PhUml\Code\Modifiers\CanBeStatic;
use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Modifiers\WithStaticModifier;
use PhUml\Code\Modifiers\WithVisibility;
use PhUml\Code\Name;
use PhUml\Code\Variables\HasType;
use PhUml\Code\Variables\Variable;
use PhUml\Code\Variables\WithVariable;
use Stringable;

/**
 * It represents an instance variable
 */
final class Attribute implements HasType, HasVisibility, CanBeStatic, Stringable
{
    use WithVisibility;
    use WithStaticModifier;
    use WithVariable;

    public function __construct(Variable $variable, Visibility $modifier, bool $isStatic = false)
    {
        $this->variable = $variable;
        $this->modifier = $modifier;
        $this->isStatic = $isStatic;
    }

    /** @return Name[] */
    public function references(): array
    {
        return $this->variable->references();
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s',
            $this->modifier,
            $this->variable
        );
    }
}
