<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Methods\Method;
use PhUml\Code\Name;
use PhUml\Code\Parameters\Parameter;

final class InterfaceBuilder extends DefinitionBuilder
{
    /** @var Name[] */
    private array $parents = [];

    /** @var Method[] */
    private array $methods = [];

    public function withAPublicMethod(string $name, Parameter ...$parameters): InterfaceBuilder
    {
        $this->methods[] = A::method($name)->public()->withParameters(...$parameters)->build();

        return $this;
    }

    public function extending(Name ...$parents): InterfaceBuilder
    {
        $this->parents = $parents;

        return $this;
    }

    public function withAMethod(Method $build): InterfaceBuilder
    {
        $this->methods[] = $build;
        return $this;
    }

    public function build(): InterfaceDefinition
    {
        return new InterfaceDefinition(
            new Name($this->name),
            $this->methods,
            $this->constants,
            $this->parents
        );
    }
}
