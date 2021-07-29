<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Name;

final class ClassBuilder extends DefinitionBuilder
{
    use MembersBuilder;

    private ?Name $parent = null;

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
}
