<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Name;
use PhUml\Fakes\NumericIdClass;

final class ClassBuilder extends DefinitionBuilder
{
    use MembersBuilder;

    protected ?Name $parent = null;

    /** @var Name[] */
    private array $interfaces = [];

    /** @var Name[] */
    private array $traits = [];

    public function extending(Name $parent): ClassBuilder
    {
        $this->parent = $parent;

        return $this;
    }

    public function implementing(Name ...$interfaces): ClassBuilder
    {
        $this->interfaces = array_merge($this->interfaces, $interfaces);

        return $this;
    }

    public function using(Name ...$traits): ClassBuilder
    {
        $this->traits = $traits;

        return $this;
    }

    public function build(): ClassDefinition
    {
        return new ClassDefinition(
            new Name($this->name),
            $this->methods,
            $this->constants,
            $this->parent,
            $this->attributes,
            $this->interfaces,
            $this->traits
        );
    }

    public function buildWithNumericId(): NumericIdClass
    {
        return new NumericIdClass(
            new Name($this->name),
            $this->methods,
            $this->constants,
            $this->parent,
            $this->attributes,
            $this->interfaces,
            $this->traits
        );
    }
}
