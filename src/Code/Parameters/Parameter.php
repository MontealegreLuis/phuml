<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Parameters;

use PhUml\Code\Variables\HasType;
use PhUml\Code\Variables\Variable;
use PhUml\Code\Variables\WithVariable;

final class Parameter implements HasType
{
    use WithVariable;

    private bool $isVariadic;

    private bool $isByReference;

    public function __construct(Variable $variable, bool $isVariadic = false, bool $isByReference = false)
    {
        $this->variable = $variable;
        $this->isVariadic = $isVariadic;
        $this->isByReference = $isByReference;
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
