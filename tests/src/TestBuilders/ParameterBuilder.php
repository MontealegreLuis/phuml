<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Parameters\Parameter;

final class ParameterBuilder
{
    private ?string $type = null;

    public function __construct(private string $name)
    {
    }

    public function withType(string $type): ParameterBuilder
    {
        $this->type = $type;

        return $this;
    }

    public function build(): Parameter
    {
        return new Parameter(A::variable($this->name)->withType($this->type)->build());
    }
}
