<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Parameters\Parameter;

final class ParameterBuilder
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    public function __construct(string $name)
    {
        $this->name = $name;
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
