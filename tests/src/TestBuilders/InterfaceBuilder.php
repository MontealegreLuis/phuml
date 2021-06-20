<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Methods\Method;
use PhUml\Code\Name;
use PhUml\Code\Parameters\Parameter;
use PhUml\Fakes\NumericIdInterface;

final class InterfaceBuilder extends DefinitionBuilder
{
    /** @var Name[] */
    protected $parents = [];

    /** @var Method[] */
    private $methods = [];

    public function withAPublicMethod(string $name, Parameter ...$parameters): InterfaceBuilder
    {
        $this->methods[] = Method::public($name, $parameters);

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

    /** @return InterfaceDefinition */
    public function build()
    {
        return new InterfaceDefinition(
            Name::from($this->name),
            $this->methods,
            $this->constants,
            $this->parents
        );
    }

    /** @return NumericIdInterface */
    public function buildWithNumericId()
    {
        return new NumericIdInterface(
            Name::from($this->name),
            $this->methods,
            $this->constants,
            $this->parents
        );
    }
}
