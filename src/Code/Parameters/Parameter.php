<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Parameters;

use PhUml\Code\Name;
use PhUml\Code\Variables\HasType;
use PhUml\Code\Variables\Variable;
use PhUml\Code\Variables\WithVariable;
use Stringable;

final class Parameter implements HasType, Stringable
{
    use WithVariable;

    public function __construct(
        Variable $variable,
        private bool $isVariadic = false,
        private bool $isByReference = false
    ) {
        $this->variable = $variable;
    }

    /** @return Name[] */
    public function references(): array
    {
        return $this->variable->references();
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s%s',
            $this->isVariadic ? '...' : '',
            $this->isByReference ? '&' : '',
            $this->variable
        );
    }
}
