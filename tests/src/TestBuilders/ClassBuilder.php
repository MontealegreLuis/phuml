<?php declare(strict_types=1);
/**
 * PHP version 7.2
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

    /** @var Name */
    protected $parent;

    /** @var Name[] */
    private $interfaces = [];

    /** @var Name[] */
    private $traits = [];

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

    /** @return ClassDefinition */
    public function build()
    {
        return new ClassDefinition(
            Name::from($this->name),
            $this->methods,
            $this->constants,
            $this->parent,
            $this->attributes,
            $this->interfaces,
            $this->traits
        );
    }

    /** @return NumericIdClass */
    public function buildWithNumericId()
    {
        return new NumericIdClass(
            Name::from($this->name),
            $this->methods,
            $this->constants,
            $this->parent,
            $this->attributes,
            $this->interfaces,
            $this->traits
        );
    }
}
